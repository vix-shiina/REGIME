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
        $currentSolde = $traitement->getUserSolde($userId);

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
            'currentSolde' => $currentSolde,
        ]);
    }

    public function addSolde()
    {
        $session = service('session');
        $userId = (int) $session->get('user_id');

        if (empty($userId)) {
            return redirect()->to('/SignIn');
        }

        $traitement = new TraitementProfil();

        return view('Solde/Add', [
            'currentSolde' => $traitement->getUserSolde($userId),
        ]);
    }

    public function addSoldePost()
    {
        $session = service('session');
        $userId = (int) $session->get('user_id');

        if (empty($userId)) {
            return redirect()->to('/SignIn');
        }

        $code = trim((string) $this->request->getPost('code'));
        $traitement = new TraitementProfil();
        $result = $traitement->applyCodeToSolde($userId, $code);

        if (!empty($result['success'])) {
            $session->setFlashdata('flash_success', $result['message'] ?? 'Solde ajouté.');
            return redirect()->to('/profil');
        }

        $session->setFlashdata('flash_error', $result['message'] ?? 'Erreur lors de l’ajout du solde.');
        return redirect()->to('/profil/solde');
    }

    public function checkSoldeCode()
    {
        $session = service('session');
        $userId = (int) $session->get('user_id');

        if (empty($userId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Non autorisé.',
            ])->setStatusCode(401);
        }

        $code = trim((string) $this->request->getPost('code'));
        $traitement = new TraitementProfil();
        $result = $traitement->checkCodeForSolde($code);

        return $this->response->setJSON($result);
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
            // Update session user_name so other pages (Myhome, header) show the new prenom immediately
            $newPrenom = $this->request->getPost('prenom');
            if (!empty($newPrenom)) {
                $session->set('user_name', $newPrenom);
            }
        } else {
            $session->setFlashdata('flash_error', $result['message'] ?? 'Erreur lors de la mise à jour.');
        }

        return redirect()->to('/profil');
    }
}
