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
}