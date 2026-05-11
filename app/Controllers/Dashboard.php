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

        $evolutionModel = new EvolutionModel();
        $regimeModel = new RegimeModel();
        $sportModel = new SportModel();
        
        // Get current taille and poids from UserInfo
        $db = db_connect();
        $userInfo = $db->table('UserInfo')
            ->select('Taille, Poids')
            ->where('UserId', $userId)
            ->get()
            ->getRowArray();
        $currentTaille = !empty($userInfo['Taille']) ? (float) $userInfo['Taille'] : null;
        $initialPoids = !empty($userInfo['Poids']) ? (float) $userInfo['Poids'] : null;
        
        // Get evolution history
        $historique = $evolutionModel->getEvolutionByUser($userId);
        
        // Initialize with both poids and IMC calculated
        $historiqueWithIMC = [];
        
        // Add initial weight as first entry if it exists and not already in history
        if (!empty($initialPoids) && !empty($currentTaille)) {
            $poidsInitialExists = false;
            foreach ($historique as $item) {
                if ($item['Poids'] == $initialPoids) {
                    $poidsInitialExists = true;
                    break;
                }
            }
            
            if (!$poidsInitialExists) {
                $initialIMC = round($initialPoids / (($currentTaille / 100) ** 2), 2);
                $historiqueWithIMC[] = [
                    'DateEvolution' => 'Poids initial',
                    'Poids' => $initialPoids,
                    'IMC' => $initialIMC
                ];
            }
        }
        
        // Process all evolution entries and calculate IMC
        foreach ($historique as $item) {
            $poids = !empty($item['Poids']) ? (float) $item['Poids'] : 0;
            $imc = ($currentTaille > 0 && $poids > 0) ? round($poids / (($currentTaille / 100) ** 2), 2) : null;
            $item['IMC'] = $imc;
            $historiqueWithIMC[] = $item;
        }

        // Calculate statistics
        $stats = $this->calculateStatistics($historiqueWithIMC, $userId, $db);
        
        // Get regime and sport data for calendar
        $currentRegime = $db->table('RegimeUser')
            ->select('RegimeUser.*, r.RegimeNom')
            ->join('REGIME r', 'r.Id = RegimeUser.RegimeId')
            ->where('RegimeUser.UserId', $userId)
            ->orderBy('RegimeUser.DateDebut', 'DESC')
            ->limit(1)
            ->get()
            ->getRow();
        
        $currentSport = $db->table('SportUser')
            ->select('SportUser.*, s.SportNom')
            ->join('SPORT s', 's.Id = SportUser.SportId')
            ->where('SportUser.UserId', $userId)
            ->orderBy('SportUser.DateDebut', 'DESC')
            ->limit(1)
            ->get()
            ->getRow();

        $data = [
            'historique' => $historiqueWithIMC,
            'regimes'    => $regimeModel->getRegimesWithDetails(),
            'sports'     => $sportModel->getSportsWithTypes(),
            'userId'     => $userId,
            'currentRegime' => $currentRegime,
            'currentSport' => $currentSport,
            'currentTaille' => $currentTaille,
            'stats'      => $stats,
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

        $poids = $this->request->getPost('poids');
        $dateEvolution = $this->request->getPost('dateEvolution') ?? date('Y-m-d');
        
        $evolutionModel = new EvolutionModel();
        $evolutionModel->insert([
            'UserId'         => $userId,
            'Poids'          => $poids,
            'DateEvolution'  => $dateEvolution
        ]);
        
        // Get the latest weight from Evolution and update UserInfo
        $latestEvolution = $evolutionModel->where('UserId', $userId)
                                          ->orderBy('DateEvolution', 'DESC')
                                          ->first();
        
        if (!empty($latestEvolution)) {
            $db = db_connect();
            $db->table('UserInfo')
               ->where('UserId', $userId)
               ->update(['Poids' => $latestEvolution['Poids']]);
        }
        
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

    private function calculateStatistics($historique, $userId, $db)
    {
        $stats = [
            'totalEntries' => count($historique),
            'poidsInitial' => null,
            'poidsCurrent' => null,
            'poidsPerte' => 0,
            'poidsVariationType' => 'stable',
            'imcInitial' => null,
            'imcCurrent' => null,
            'poidsMin' => null,
            'poidsMax' => null,
            'poidsMoyen' => 0,
            'regimeData' => [],
            'sportData' => [],
            'imcCategories' => [
                'maigreur' => 0,
                'normal' => 0,
                'surpoids' => 0,
                'obesite' => 0
            ]
        ];

        if (empty($historique)) {
            return $stats;
        }

        // Get initial and current weight/IMC
        $stats['poidsInitial'] = $historique[0]['Poids'] ?? null;
        $stats['imcInitial'] = $historique[0]['IMC'] ?? null;
        $lastEntry = end($historique);
        $stats['poidsCurrent'] = $lastEntry['Poids'] ?? null;
        $stats['imcCurrent'] = $lastEntry['IMC'] ?? null;

        // Calculate weight variation with explicit direction
        if (!empty($stats['poidsInitial']) && !empty($stats['poidsCurrent'])) {
            $delta = round((float) $stats['poidsCurrent'] - (float) $stats['poidsInitial'], 2);

            if ($delta > 0) {
                $stats['poidsVariationType'] = 'pris';
                $stats['poidsPerte'] = $delta;
            } elseif ($delta < 0) {
                $stats['poidsVariationType'] = 'perdu';
                $stats['poidsPerte'] = abs($delta);
            } else {
                $stats['poidsVariationType'] = 'stable';
                $stats['poidsPerte'] = 0;
            }
        }

        // Calculate min, max, average weight
        $poids = array_column($historique, 'Poids');
        $poids = array_filter($poids, fn($p) => !empty($p));
        
        if (!empty($poids)) {
            $stats['poidsMin'] = min($poids);
            $stats['poidsMax'] = max($poids);
            $stats['poidsMoyen'] = round(array_sum($poids) / count($poids), 2);
        }

        // Categorize IMC values
        foreach ($historique as $entry) {
            if (!empty($entry['IMC'])) {
                if ($entry['IMC'] < 18.5) {
                    $stats['imcCategories']['maigreur']++;
                } elseif ($entry['IMC'] < 25) {
                    $stats['imcCategories']['normal']++;
                } elseif ($entry['IMC'] < 30) {
                    $stats['imcCategories']['surpoids']++;
                } else {
                    $stats['imcCategories']['obesite']++;
                }
            }
        }

        // Get regime statistics
        $regimeStats = $db->table('RegimeUser')
            ->select('r.RegimeNom, COUNT(*) as nombre')
            ->join('REGIME r', 'r.Id = RegimeUser.RegimeId')
            ->where('RegimeUser.UserId', $userId)
            ->groupBy('RegimeUser.RegimeId')
            ->get()
            ->getResultArray();
        $stats['regimeData'] = $regimeStats;

        // Get sport statistics
        $sportStats = $db->table('SportUser')
            ->select('s.SportNom, COUNT(*) as nombre')
            ->join('SPORT s', 's.Id = SportUser.SportId')
            ->where('SportUser.UserId', $userId)
            ->groupBy('SportUser.SportId')
            ->get()
            ->getResultArray();
        $stats['sportData'] = $sportStats;

        return $stats;
    }
}