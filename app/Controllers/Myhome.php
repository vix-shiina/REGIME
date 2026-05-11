<?php

namespace App\Controllers;

class Myhome extends BaseController
{
    public function index()
    {
        $session = service('session');

        $userId = $session->get('user_id');

        if (empty($userId)) {

            return redirect()->to('/SignIn');
        }

        $db = db_connect();

        $user = $db->table('USER u')
            ->select('u.Prenom, us.Solde')
            ->join('UserSolde us', 'us.UserId = u.Id', 'left')
            ->where('u.Id', (int) $userId)
            ->get()
            ->getRowArray();

        $userName = $user['Prenom'] ?? 'Utilisateur';

        $session->set('user_name', $userName);

        $userSolde = $user['Solde'] ?? 0;

        $currentRegime = $db->table('RegimeUser ru')
            ->select('
                ru.Id,
                ru.DateDebut,
                ru.DureeEnJours,
                ru.Paiement,

                r.Id as RegimeId,
                r.RegimeNom,
                r.TypeDeRegimeId,
                r.PrixJournaliere,
                r.EfficacitePoidsParSemaine
            ')
            ->join('REGIME r', 'r.Id = ru.RegimeId')
            ->where('ru.UserId', (int) $userId)
            ->orderBy('ru.Id', 'DESC')
            ->get()
            ->getRowArray();

        if (!empty($currentRegime)) {

            $remainingDays = null;

            $isActive = false;

            try {

                $startDate = new \DateTimeImmutable(
                    $currentRegime['DateDebut']
                );

                $durationDays = (int) (
                    $currentRegime['DureeEnJours'] ?? 0
                );

                $today = new \DateTimeImmutable();

                $endDate = $startDate->modify(
                    '+' . $durationDays . ' days'
                );

                $remainingDays = max(
                    0,
                    (int) $today->diff($endDate)->format('%r%a')
                );

                $isActive = $today <= $endDate;

            } catch (\Throwable $e) {

                $remainingDays = null;
            }

            $currentRegime['remaining_days'] = $remainingDays;

            $currentRegime['is_active'] = $isActive;
        }

        return view('Myhome', [

            'userName' => $userName,

            'userSolde' => $userSolde,

            'currentRegime' => $currentRegime
        ]);
    }
}