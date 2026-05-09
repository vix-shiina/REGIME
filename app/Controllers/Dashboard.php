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
    $userId = $session->get('user_id');

    if (empty($userId)) {
        return redirect()->to('/SignIn');
    }

    $db = \Config\Database::connect();

   
$userInfo = $db->table('UserInfo')->where('UserId', $userId)->get()->getRowArray();
$tailleBase = ($userInfo && $userInfo['Taille'] > 0) ? (float)$userInfo['Taille'] : 1.70;
$tailleM = ($tailleBase > 3) ? $tailleBase / 100 : $tailleBase;

$dernierSuivi = $db->table('Evolution')
                   ->where('UserId', $userId)
                   ->orderBy('DateEvolution', 'DESC')
                   ->orderBy('Id', 'DESC') 
                   ->get()->getRowArray();

$poidsActuel = $dernierSuivi ? (float)$dernierSuivi['Poids'] : ($userInfo ? (float)$userInfo['Poids'] : 0);

$imc = 0;
if ($poidsActuel > 0 && $tailleM > 0) {
    $imc = round($poidsActuel / ($tailleM * $tailleM), 1);
}
    $evolutionModel = new \App\Models\EvolutionModel();
    $regimeModel = new \App\Models\RegimeModel();
    $sportModel = new \App\Models\SportModel();

    $etat = "Inconnu";
   $couleur = "#ccc";
   $idTypeRegime = 1; 

if ($imc > 0) {
    if ($imc < 18.5) { 
        $etat = "Maigreur"; 
        $couleur = "#ffcc00"; 
        $idTypeRegime = 1; 
    }
    elseif ($imc < 25) { 
        $etat = "Normal"; 
        $couleur = "#bada55"; 
        $idTypeRegime = [1, 2, 3];
    }
    elseif ($imc < 30) { 
        $etat = "Surpoids"; 
        $couleur = "#ff6600"; 
        $idTypeRegime = 2; 
    }
    else { 
        $etat = "Obésité"; 
        $couleur = "#cc0000"; 
        $idTypeRegime = 2; 
    }
}

    $data = [
        'historique'   => $evolutionModel->getEvolutionByUser($userId),
        'sports'       => $sportModel->getSportsWithTypes(),
        'userId'       => $userId,
        'poidsActuel'  => $poidsActuel,
        'imc'          => $imc,
        'etat'         => $etat,
        'couleur_imc'  => $couleur,
        'page_title'   => 'Mon Dashboard'
    ];
    $data['regimes'] = $regimeModel->getRegimesParTypesMultiples($idTypeRegime);

    return view('Dashboard', $data);
}

   public function ajouterPoids()
{
    $session = service('session');
    $userId = $session->get('user_id'); 

    if (empty($userId)) {
        return redirect()->to('/SignIn');
    }

    $poids = $this->request->getPost('poids');

    if ($poids > 0) {
        $evolutionModel = new EvolutionModel();
        $evolutionModel->insert([
            'UserId'         => $userId, 
            'Poids'          => $poids,
            'DateEvolution'  => date('Y-m-d')
        ]);
    }
    
    return redirect()->to('/dashboard');
}
}