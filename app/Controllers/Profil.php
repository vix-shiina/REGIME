<?php

namespace App\Controllers;

use App\Traitements\TraitementProfil;

class Profil extends BaseController
{
    public function index()
    {
        $session = service('session');
        $userId = (int) $session->get('user_id');

        if (empty($userId)) {
            return redirect()->to('/SignIn');
        }

        $traitement = new TraitementProfil();
        $user = $traitement->getProfile($userId);
        $genres = $traitement->getGenres();
        $currentRegime = $traitement->getCurrentRegime($userId);

        if ($currentRegime) {
            try {
                $start = new \DateTime($currentRegime['DateDebut']);
                $duration = (int) ($currentRegime['DureeEnJours'] ?? 0);
                $end = (clone $start)->modify("+{$duration} days");
                $today = new \DateTime();
                $remaining = $today > $end ? 0 : (int) $today->diff($end)->format('%a');
                $currentRegime['remaining_days'] = $remaining;
            } catch (\Throwable $e) {
                $currentRegime['remaining_days'] = null;
            }
        }

        if (!$user) {
            $session->destroy();
            return redirect()->to('/SignIn');
        }

        return view('Profil', [
            'user' => $user,
            'genres' => $genres,
            'currentRegime' => $currentRegime ?? null,
        ]);
    }

    public function update()
    {
        $session = service('session');
        $userId = (int) $session->get('user_id');

        if (empty($userId)) {
            return redirect()->to('/SignIn');
        }

        $traitement = new TraitementProfil();
        $result = $traitement->updateProfile($userId, $this->request->getPost());

        if (!empty($result['success'])) {
            $session->setFlashdata('flash_success', $result['message'] ?? 'Profil mis à jour.');
        } else {
            $session->setFlashdata('flash_error', $result['message'] ?? 'Erreur lors de la mise à jour.');
        }

        return redirect()->to('/profil');
    }
}
