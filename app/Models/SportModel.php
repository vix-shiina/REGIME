<?php
namespace App\Models;
use CodeIgniter\Model;

class SportModel extends Model {
    protected $table = 'SPORT';
    protected $primaryKey = 'Id';
    protected $allowedFields = ['SportNom', 'TypeDeSportId', 'EfficacitePoids'];

    public function getSportsWithTypes() {
        return $this->select('SPORT.*, TypeDeSport.TypeDeSport as Categorie')
                    ->join('TypeDeSport', 'TypeDeSport.Id = SPORT.TypeDeSportId')
                    ->findAll();
    }

    public function getTypeDeSportOptions()
    {
        return $this->db->table('TypeDeSport')
            ->select('Id, TypeDeSport AS TypeNom')
            ->get()
            ->getResultArray();
    }

    public function createSport($nom, $typeId, $efficacite) {
        $data = [
            'SportNom' => $nom,
            'TypeDeSportId' => $typeId,
            'EfficacitePoids' => $efficacite
        ];
        return $this->insert($data);
    }

    public function updateSport($id, $nom, $typeId, $efficacite) {
        $data = [
            'SportNom' => $nom,
            'TypeDeSportId' => $typeId,
            'EfficacitePoids' => $efficacite
        ];
        return $this->update($id, $data);
    }

    public function deleteSport($id) {
        return $this->delete($id);
    }
}