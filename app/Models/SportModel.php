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
}