<?php

namespace App\Controllers;

use App\Models\EvolutionModel;
use App\Models\RegimeModel;
use App\Models\SportModel;

class Dashboard extends BaseController
{
   public function index()
    {
        $session = service('session');
        $userId = $session->get('user_id'); // On récupère l'ID ici

        // Sécurité : si non connecté, retour au login
        if (empty($userId)) {
            return redirect()->to('/SignIn');
        }

        $evolutionModel = new EvolutionModel();
        $regimeModel = new RegimeModel();
        $sportModel = new SportModel();

        $data = [
            'historique' => $evolutionModel->getEvolutionByUser($userId),
            'regimes'    => $regimeModel->getRegimesWithDetails(),
            'sports'     => $sportModel->getSportsWithTypes(),
            'userId'     => $userId,
            'page_title' => 'Mon Suivi'
        ];

        return view('Dashboard', $data);
    }
   public function ajouterPoids()
    {
        $session = service('session');
        $userId = $session->get('user_id'); // On récupère l'ID pour l'insertion

        if (empty($userId)) {
            return redirect()->to('/SignIn');
        }

        $evolutionModel = new EvolutionModel();
        $evolutionModel->insert([
            'UserId'         => $userId, // Utilise l'ID de la session
            'Poids'          => $this->request->getPost('poids'),
            'DateEvolution'  => date('Y-m-d')
        ]);
        
        return redirect()->to('/dashboard');
    }

    public function selectRegime($regimeId = null)
    {
        $session = service('session');
        $userId = $session->get('user_id');

        if (empty($userId)) {
            return redirect()->to('/SignIn');
        }

        $regimeId = $regimeId ?? $this->request->getPost('regime_id');
        $durationDays = (int) ($this->request->getPost('duree_jours') ?? 30);

        if (empty($regimeId)) {
            return redirect()->to('/dashboard')->with('error', 'Veuillez sélectionner un régime.');
        }

        $regimeModel = new RegimeModel();
        $regimeModel->selectRegimeForUser($userId, $regimeId, $durationDays);

        return redirect()->to('/dashboard')->with('success', 'Régime sélectionné avec succès.');
    }

    public function selectSport($sportId = null)
    {
        $session = service('session');
        $userId = $session->get('user_id');

        if (empty($userId)) {
            return redirect()->to('/SignIn');
        }

        $sportId = $sportId ?? $this->request->getPost('sport_id');
        $durationDays = (int) ($this->request->getPost('duree_jours') ?? 30);

        if (empty($sportId)) {
            return redirect()->to('/dashboard')->with('error', 'Veuillez sélectionner un sport.');
        }

        $sportModel = new SportModel();
        $sportModel->selectSportForUser($userId, $sportId, $durationDays);

        return redirect()->to('/dashboard')->with('success', 'Sport sélectionné avec succès.');
    }
}