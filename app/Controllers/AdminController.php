<?php
    namespace App\Controllers;
    
    use App\Models\ClientModel;
    use App\Models\CodeModel;
    use App\Models\RegimeModel;
    use App\Models\SportModel;

    class AdminController extends BaseController
    {
        public function manageRegimes()
        {
            $regimeModel = new RegimeModel();
            $typeDeRegimeOptions = $this->getTypeDeRegimeOptions();
            $regimes = $regimeModel->getRegimesWithDetails();

            return view('Admin/GererRegime', [
                'typeDeRegimeOptions' => $typeDeRegimeOptions,
                'regimes' => $regimes,
            ]);
        }

        public function manageSports()
        {
            $sportModel = new SportModel();
            $sports = $sportModel->getSportsWithTypes();
            $typeOptions = $sportModel->getTypeDeSportOptions();

            return view('Admin/GererSport', [
                'sports' => $sports,
                'typeOptions' => $typeOptions,
            ]);
        }

        public function manageCodes()
        {
            $codeModel = new CodeModel();

            return view('Admin/GererCode', [
                'codes' => $codeModel->getCodes(),
            ]);
        }

        public function manageClients()
        {
            $clientModel = new ClientModel();
            $search = trim((string) $this->request->getGet('q'));
            $page = max(1, (int) $this->request->getGet('page') ?: 1);
            $perPage = 12;
            $offset = ($page - 1) * $perPage;

            $clients = $clientModel->getClientsWithCurrentRegime($search, $perPage, $offset);
            $total = $clientModel->countClients($search);
            $totalPages = max(1, (int) ceil($total / $perPage));

            return view('Admin/GererClient', [
                'clients' => $clients,
                'search' => $search,
                'page' => $page,
                'perPage' => $perPage,
                'totalPages' => $totalPages,
                'total' => $total,
            ]);
        }

        public function createRegime()
        {
            $regimeModel = new RegimeModel();
            $regimeModel->createRegime(
                $this->request->getPost('RegimeNom'),
                $this->request->getPost('TypeDeRegimeId'),
                $this->request->getPost('PrixJournaliere'),
                $this->request->getPost('EfficacitePoids'),
                $this->request->getPost('Viande'),
                $this->request->getPost('Poisson'),
                $this->request->getPost('Volailles')
            );
            return redirect()->to('/admin/regimes');
        }

        public function createSport()
        {
            $sportModel = new SportModel();
            $sportModel->createSport(
                $this->request->getPost('SportNom'),
                $this->request->getPost('TypeDeSportId'),
                $this->request->getPost('EfficacitePoids')
            );
            return redirect()->to('/admin/sports');
        }

        public function createCode()
        {
            $codeModel = new CodeModel();
            $codeModel->createCode(
                $this->request->getPost('Code'),
                $this->request->getPost('Valeur'),
                $this->request->getPost('DateExpiration'),
                $this->request->getPost('Actif') ? 1 : 0
            );
            return redirect()->to('/admin/codes');
        }

        public function deleteRegime($id)
        {
            $regimeModel = new RegimeModel();
            $regimeModel->deleteRegime($id);
            return redirect()->to('/admin/regimes');
        }

        public function deleteSport($id)
        {
            $sportModel = new SportModel();
            $sportModel->deleteSport($id);
            return redirect()->to('/admin/sports');
        }

        public function deleteCode($id)
        {
            $codeModel = new CodeModel();
            $codeModel->deleteCode($id);
            return redirect()->to('/admin/codes');
        }

        public function editRegime($id)
        {
            $regimeModel = new RegimeModel();
            $regime = $regimeModel->getRegimeWithDetails($id);
            if (!$regime) {
                return redirect()->to('/admin/regimes')->with('error', 'Régime introuvable');
            }

            $typeDeRegimeOptions = $this->getTypeDeRegimeOptions();
            return view('Admin/EditRegime', [
                'regime' => $regime,
                'typeDeRegimeOptions' => $typeDeRegimeOptions,
            ]);
        }

        public function updateRegime($id)
        {
            $regimeModel = new RegimeModel();
            $regimeModel->updateRegime(
                $id,
                $this->request->getPost('RegimeNom'),
                $this->request->getPost('TypeDeRegimeId'),
                $this->request->getPost('PrixJournaliere'),
                $this->request->getPost('EfficacitePoids'),
                $this->request->getPost('Viande'),
                $this->request->getPost('Poisson'),
                $this->request->getPost('Volailles')
            );
            return redirect()->to('/admin/regimes');
        }

        public function getTypeDeRegimeOptions()
        {
            $regimeModel = new RegimeModel();
            return $regimeModel->getTypeDeRegimeOptions();
        }

        public function editSport($id)
        {
            $sportModel = new SportModel();
            $sport = $sportModel->find($id);
            if (!$sport) {
                return redirect()->to('/admin/sports')->with('error', 'Sport introuvable');
            }

            $typeOptions = $sportModel->getTypeDeSportOptions();
            return view('Admin/EditSport', [
                'sport' => $sport,
                'typeOptions' => $typeOptions,
            ]);
        }

        public function updateSport($id)
        {
            $sportModel = new SportModel();
            $sportModel->updateSport(
                $id,
                $this->request->getPost('SportNom'),
                $this->request->getPost('TypeDeSportId'),
                $this->request->getPost('EfficacitePoids')
            );
            return redirect()->to('/admin/sports');
        }

        public function editCode($id)
        {
            $codeModel = new CodeModel();
            $code = $codeModel->getCodeById($id);
            if (!$code) {
                return redirect()->to('/admin/codes')->with('error', 'Code introuvable');
            }

            return view('Admin/EditCode', [
                'code' => $code,
            ]);
        }

        public function updateCode($id)
        {
            $codeModel = new CodeModel();
            $codeModel->updateCode(
                $id,
                $this->request->getPost('Code'),
                $this->request->getPost('Valeur'),
                $this->request->getPost('DateExpiration'),
                $this->request->getPost('Actif') ? 1 : 0
            );
            return redirect()->to('/admin/codes');
        }
    }
?>