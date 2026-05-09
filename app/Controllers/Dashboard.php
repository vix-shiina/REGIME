<?php

namespace App\Controllers;

use App\Models\EvolutionModel;
use App\Models\RegimeModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $evolutionModel = new EvolutionModel();
        $regimeModel = new RegimeModel();

        $userId = 1; 

        // 1. On récupère les données
        $historique = $evolutionModel->getEvolutionByUser($userId);
        $regimesSuggérés = $regimeModel->getRegimesWithDetails();

        
        $data = [
            'historique' => $historique,
            'regimes'    => $regimesSuggérés,
            'userId'     => $userId,
            'page_title' => 'Mon Suivi Régime'
        ];

        return view('Dashboard', $data);
    }

    public function ajouterPoids()
    {
        $evolutionModel = new EvolutionModel();
        $evolutionModel->insert([
            'UserId'         => $this->request->getPost('userId'),
            'Poids'          => $this->request->getPost('poids'),
            'DateEvolution'  => date('Y-m-d')
        ]);
        return redirect()->to('/dashboard');
    }
}