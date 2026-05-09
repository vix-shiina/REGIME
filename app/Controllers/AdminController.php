<?php
    namespace App\Controllers;
    
    use App\Models\RegimeModel;
    use App\Models\SportModel;

    class AdminController extends BaseController
    {
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
    }
?>