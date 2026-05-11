<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Mon profil</title>
    <link rel="stylesheet" href="/assets/css/profile.css">
    <script defer src="/assets/js/profile.js"></script>
</head>
<body>
<?php $user = $user ?? []; ?>
<?php $genres = $genres ?? []; ?>
<?php $isGoldClient = (($currentRegime['Paiement'] ?? '') === 'Paiement unique'); ?>

<?php echo view('partials/Header'); ?>

<main class="profile-page">
    <section class="profile-hero card">
        <div class="profile-avatar" aria-hidden="true">
            <?php echo strtoupper(substr($user['Prenom'] ?? 'U', 0, 1)); ?>
        </div>
        <div class="profile-hero-content">
            <p class="eyebrow">Compte connecté</p>
            <h1><?php echo htmlspecialchars(($user['Prenom'] ?? '') . ' ' . ($user['Nom'] ?? '')); ?></h1>
            <p class="subtitle">
                <?php echo htmlspecialchars($user['UserType'] ?? 'Utilisateur'); ?>
                <?php if (!empty($user['Genre'])): ?>
                    • <?php echo htmlspecialchars($user['Genre']); ?>
                <?php endif; ?>
            </p>
        </div>
        <div class="profile-actions">
            <a class="btn btn-secondary" href="/dashboard">Retour dashboard</a>
            <a class="btn btn-secondary" href="/profil/solde">Ajouter du solde</a>
            <a class="btn btn-primary" href="/logout">Se déconnecter</a>
        </div>
    </section>


    <?php echo view('partials/Flash'); ?>

    <section class="profile-stage">
        <section class="profile-panel is-active" data-profile-panel="overview">
            <div class="overview-grid">
                <article class="card summary-card">
                    <div class="summary-head">
                        <div>
                            <p class="section-kicker">Mes Informations</p>
                            <h2>Vue d’ensemble</h2>
                        </div>
                        <button class="settings-btn" type="button" data-profile-action="edit" aria-label="Modifier le profil">
                            <img src="/assets/images/icons/setting.png" alt="Modifier" width="20" height="20">
                        </button>
                    </div>

                    <dl class="summary-grid">
                        <div>
                            <dt>Nom complet</dt>
                            <dd><?php echo htmlspecialchars(trim(($user['Prenom'] ?? '') . ' ' . ($user['Nom'] ?? ''))); ?></dd>
                        </div>
                        <div>
                            <dt>Email</dt>
                            <dd><?php echo htmlspecialchars($user['Email'] ?? '-'); ?></dd>
                        </div>
                        <div>
                            <dt>Genre</dt>
                            <dd><?php echo htmlspecialchars($user['Genre'] ?? '-'); ?></dd>
                        </div>
                        <div>
                            <dt>Type de compte</dt>
                            <dd><?php echo htmlspecialchars($user['UserType'] ?? '-'); ?></dd>
                        </div>
                        <div>
                            <dt>Âge</dt>
                            <dd><?php echo htmlspecialchars((string) ($user['Age'] ?? '-')); ?></dd>
                        </div>
                        <div>
                            <dt>Taille</dt>
                            <dd><?php echo !empty($user['Taille']) ? htmlspecialchars((string) $user['Taille']) . ' cm' : '-'; ?></dd>
                        </div>
                        <div>
                            <dt>Poids</dt>
                            <dd><?php echo !empty($user['Poids']) ? htmlspecialchars((string) $user['Poids']) . ' kg' : '-'; ?></dd>
                        </div>
                        <div>
                            <dt>Traitement actuel</dt>
                            <dd>
                                <?php echo htmlspecialchars($currentRegime['RegimeNom'] ?? '-'); ?><br>
                                Début : <?php echo htmlspecialchars($currentRegime['DateDebut'] ?? '-'); ?><br>
                                Durée : <?php echo htmlspecialchars((string) ($currentRegime['DureeEnJours'] ?? '-')); ?> jours<br>
                                Restant : <?php echo isset($currentRegime['remaining_days']) ? htmlspecialchars((string) $currentRegime['remaining_days']) . ' jour(s)' : '-'; ?><br>
                                Statut : <?php echo $isGoldClient ? 'Client Gold' : 'Client standard'; ?>
                            </dd>
                        </div>
                    </dl>
                </article>

            </div>
        </section>

        <section class="profile-panel" data-profile-panel="edit">
            <form class="profile-form" id="profileForm" method="post" action="/profil">
                <div class="profile-grid">
                    <article class="card info-card">
                        <div class="summary-head">
                            <div>
                                <p class="section-kicker">Page d’édition</p>
                                <h2>Informations personnelles</h2>
                            </div>
                        </div>
                        <div class="form-grid">
                            <label>
                                <span>Nom</span>
                                <input type="text" name="nom" value="<?php echo htmlspecialchars($user['Nom'] ?? ''); ?>" disabled required>
                            </label>
                            <label>
                                <span>Prénom</span>
                                <input type="text" name="prenom" value="<?php echo htmlspecialchars($user['Prenom'] ?? ''); ?>" disabled required>
                            </label>
                            <label>
                                <span>Email</span>
                                <input type="email" name="email" value="<?php echo htmlspecialchars($user['Email'] ?? ''); ?>" disabled required>
                            </label>
                            <label>
                                <span>Mot de passe</span>
                                <input type="password" name="password" value="" disabled placeholder="Nouveau mot de passe">
                            </label>
                            <label>
                                <span>Genre</span>
                                <select name="genre_id" disabled required>
                                    <option value="">-- Choisir --</option>
                                    <?php foreach ($genres as $genre): ?>
                                        <option value="<?php echo htmlspecialchars((string) $genre['Id']); ?>" <?php echo ((int) ($user['GenreId'] ?? 0) === (int) $genre['Id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($genre['Genre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                        </div>
                    </article>

                    <article class="card info-card">
                        <div class="summary-head">
                            <div>
                                <p class="section-kicker">Santé</p>
                                <h2>Infos santé</h2>
                            </div>
                        </div>
                        <div class="form-grid">
                            <label>
                                <span>Âge</span>
                                <input type="number" name="age" min="0" value="<?php echo htmlspecialchars((string) ($user['Age'] ?? '')); ?>" disabled>
                            </label>
                            <label>
                                <span>Taille (cm)</span>
                                <input type="number" step="0.1" name="taille" value="<?php echo htmlspecialchars((string) ($user['Taille'] ?? '')); ?>" disabled>
                            </label>
                            <label>
                                <span>Poids (kg)<?php echo !empty($user['Poids']) ? ' <span style="color: #888; font-size: 0.85em;">(Suivi via Evolution)</span>' : ''; ?></span>
                                <input type="number" step="0.1" name="poids" value="<?php echo htmlspecialchars((string) ($user['Poids'] ?? '')); ?>" <?php echo !empty($user['Poids']) ? 'disabled title="Le poids est suivi automatiquement via votre historique d\'évolution"' : ''; ?>>
                            </label>
                        </div>
                    </article>
                </div>

                <section class="card session-card">
                    <p>Une fois valide, il n'est plus possible de restaurer les modifications</p>
                    <div class="profile-footer-actions">
                        <button class="btn btn-secondary" type="button" id="editBtn">Modifier</button>
                        <button class="btn btn-primary is-hidden" type="submit" id="saveBtn">Valider</button>
                        <button class="btn btn-ghost is-hidden" type="button" id="cancelBtn">Annuler</button>
                    </div>
                </section>
            </form>
        </section>
    </section>
</main>

<?php echo view('partials/Footer'); ?>

</body>
</html>
