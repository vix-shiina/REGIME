<?php

namespace App\Traitements;

class TraitementProfil
{
    protected \PDO $pdo;

    public function __construct()
    {
        $dsn = 'mysql:host=127.0.0.1;dbname=REGIME;charset=utf8mb4';
        $this->pdo = new \PDO($dsn, 'root', '', [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    }

    public function getProfile(int $userId): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT u.Id, u.Nom, u.Prenom, u.Email, u.Password, u.UserTypeId, u.GenreId,
                    gt.Genre, ut.UserType, ui.Age, ui.Taille, ui.Poids
             FROM USER u
             LEFT JOIN Genre gt ON gt.Id = u.GenreId
             LEFT JOIN UserType ut ON ut.Id = u.UserTypeId
             LEFT JOIN UserInfo ui ON ui.UserId = u.Id
             WHERE u.Id = ?'
        );
        $stmt->execute([$userId]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function getGenres(): array
    {
        $stmt = $this->pdo->query('SELECT Id, Genre FROM Genre ORDER BY Genre ASC');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getCurrentRegime(int $userId): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT ru.Id AS RegUserId, ru.RegimeId, ru.DateDebut, ru.DureeEnJours, ru.Paiement, r.RegimeNom
             FROM RegimeUser ru
             LEFT JOIN REGIME r ON r.Id = ru.RegimeId
             WHERE ru.UserId = ?
             ORDER BY ru.DateDebut DESC
             LIMIT 1'
        );
        $stmt->execute([$userId]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function getUserSolde(int $userId): float
    {
        $stmt = $this->pdo->prepare('SELECT Solde FROM UserSolde WHERE UserId = ? LIMIT 1');
        $stmt->execute([$userId]);
        $value = $stmt->fetchColumn();

        return $value !== false ? (float) $value : 0.0;
    }

    public function checkCodeForSolde(string $codeValue): array
    {
        $codeValue = trim($codeValue);

        if ($codeValue === '') {
            return [
                'success' => false,
                'message' => 'Veuillez saisir un code.',
            ];
        }

        $stmt = $this->pdo->prepare(
            'SELECT c.Id, c.Code, c.Valeur, c.DateExpiration, c.Actif, ac.Id AS ApplicationId
             FROM Code c
             LEFT JOIN ApplicationCode ac ON ac.CodeId = c.Id
               WHERE UPPER(TRIM(c.Code)) = UPPER(TRIM(?))
             LIMIT 1'
        );
        $stmt->execute([$codeValue]);
        $code = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$code) {
            return [
                'success' => false,
                'message' => 'Code introuvable.',
            ];
        }

        if (!empty($code['ApplicationId'])) {
            return [
                'success' => false,
                'message' => 'Ce code a déjà été utilisé.',
            ];
        }

        if ((int) ($code['Actif'] ?? 0) !== 1) {
            return [
                'success' => false,
                'message' => 'Ce code n’est plus valide.',
            ];
        }

        if (!empty($code['DateExpiration'])) {
            $today = new \DateTimeImmutable('today');
            $expiry = new \DateTimeImmutable($code['DateExpiration']);

            if ($expiry < $today) {
                return [
                    'success' => false,
                    'message' => 'Ce code est expiré.',
                ];
            }
        }

        $amount = round((float) ($code['Valeur'] ?? 0), 2);
        if ($amount <= 0) {
            return [
                'success' => false,
                'message' => 'Ce code ne contient aucun montant.',
            ];
        }

        return [
            'success' => true,
            'message' => 'Code valide.',
            'amount' => $amount,
            'code' => $code['Code'],
        ];
    }

    public function applyCodeToSolde(int $userId, string $codeValue): array
    {
        $codeValue = trim($codeValue);

        if ($codeValue === '') {
            return [
                'success' => false,
                'message' => 'Veuillez saisir un code.',
            ];
        }

        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare(
                'SELECT c.Id, c.Code, c.Valeur, c.DateExpiration, c.Actif, ac.Id AS ApplicationId
                 FROM Code c
                 LEFT JOIN ApplicationCode ac ON ac.CodeId = c.Id
                  WHERE UPPER(TRIM(c.Code)) = UPPER(TRIM(?))
                 LIMIT 1
                 FOR UPDATE'
            );
            $stmt->execute([$codeValue]);
            $code = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$code) {
                $this->pdo->rollBack();
                return [
                    'success' => false,
                    'message' => 'Code introuvable.',
                ];
            }

            $check = $this->checkCodeForSolde($codeValue);
            if (empty($check['success'])) {
                $this->pdo->rollBack();
                return $check;
            }

            $amount = (float) $check['amount'];

            $stmt = $this->pdo->prepare('SELECT Id, Solde FROM UserSolde WHERE UserId = ? LIMIT 1 FOR UPDATE');
            $stmt->execute([$userId]);
            $soldeRow = $stmt->fetch(\PDO::FETCH_ASSOC);

            $balanceBefore = (float) ($soldeRow['Solde'] ?? 0);
            $balanceAfter = round($balanceBefore + $amount, 2);

            if ($soldeRow) {
                $updateSolde = $this->pdo->prepare('UPDATE UserSolde SET Solde = ? WHERE UserId = ?');
                $updateSolde->execute([$balanceAfter, $userId]);
            } else {
                $insertSolde = $this->pdo->prepare('INSERT INTO UserSolde (UserId, Solde) VALUES (?, ?)');
                $insertSolde->execute([$userId, $balanceAfter]);
            }

            $insertApplication = $this->pdo->prepare(
                'INSERT INTO ApplicationCode (UserId, CodeId, BalanceAvant, BalanceApres) VALUES (?, ?, ?, ?)'
            );
            $insertApplication->execute([$userId, (int) $code['Id'], $balanceBefore, $balanceAfter]);

            $this->pdo->commit();

            return [
                'success' => true,
                'message' => 'Solde ajouté avec succès.',
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
            ];
        } catch (\Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            return [
                'success' => false,
                'message' => 'Erreur lors de l’application du code.',
            ];
        }
    }

    public function updateProfile(int $userId, array $data): array
    {
        $nom = trim($data['nom'] ?? '');
        $prenom = trim($data['prenom'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = trim($data['password'] ?? '');
        $genreId = !empty($data['genre_id']) ? (int) $data['genre_id'] : 0;

        $age = trim($data['age'] ?? '');
        $taille = trim($data['taille'] ?? '');
        $poids = trim($data['poids'] ?? '');

        if ($nom === '' || $prenom === '' || $email === '' || $genreId <= 0) {
            return [
                'success' => false,
                'message' => 'Nom, prénom, email et genre sont obligatoires.',
            ];
        }

        $stmt = $this->pdo->prepare('SELECT Id FROM USER WHERE Email = ? AND Id <> ?');
        $stmt->execute([$email, $userId]);
        if ($stmt->fetch()) {
            return [
                'success' => false,
                'message' => 'Cet email est déjà utilisé par un autre compte.',
            ];
        }

        try {
            $this->pdo->beginTransaction();

            if ($password !== '') {
                $updateUser = $this->pdo->prepare(
                    'UPDATE USER SET Nom = ?, Prenom = ?, Email = ?, Password = ?, GenreId = ? WHERE Id = ?'
                );
                $updateUser->execute([$nom, $prenom, $email, $password, $genreId, $userId]);
            } else {
                $updateUser = $this->pdo->prepare(
                    'UPDATE USER SET Nom = ?, Prenom = ?, Email = ?, GenreId = ? WHERE Id = ?'
                );
                $updateUser->execute([$nom, $prenom, $email, $genreId, $userId]);
            }

            $ageValue = $age === '' ? null : (int) $age;
            $tailleValue = $taille === '' ? null : (float) $taille;
            $poidsValue = $poids === '' ? null : (float) $poids;

            $stmt = $this->pdo->prepare('SELECT Id FROM UserInfo WHERE UserId = ?');
            $stmt->execute([$userId]);
            $userInfoId = $stmt->fetchColumn();

            if ($userInfoId) {
                $updateInfo = $this->pdo->prepare('UPDATE UserInfo SET Age = ?, Taille = ?, Poids = ? WHERE UserId = ?');
                $updateInfo->execute([$ageValue, $tailleValue, $poidsValue, $userId]);
            } else {
                $insertInfo = $this->pdo->prepare('INSERT INTO UserInfo (UserId, Age, Taille, Poids) VALUES (?, ?, ?, ?)');
                $insertInfo->execute([$userId, $ageValue, $tailleValue, $poidsValue]);
            }

            $this->pdo->commit();

            return [
                'success' => true,
                'message' => 'Profil mis à jour avec succès.',
            ];
        } catch (\Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            return [
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du profil.',
            ];
        }
    }
}
