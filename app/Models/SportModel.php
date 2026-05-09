<?php
namespace App\Models;
use CodeIgniter\Model;

class SportModel extends Model {
    protected $table = 'SPORT';
    protected $primaryKey = 'Id';
    protected $allowedFields = ['SportNom', 'TypeDeSportId', 'EfficacitePoids'];

    public function getSportsWithTypes()
{
    return $this->db->table('SPORT s')
        ->select('s.*, t.TypeDeSport as Categorie') 
        ->join('TypeDeSport t', 't.Id = s.TypeDeSportId')
        ->get()
        ->getResultArray();
    }

    public function getSportsParObjectif($etat)
    {
        $builder = $this->db->table('SPORT s');
        $builder->select('s.*, t.TypeDeSport');
        $builder->join('TypeDeSport t', 's.TypeDeSportId = t.Id');

   
        if ($etat == "Surpoids" || $etat == "Obésité") {
            $builder->where('s.EfficacitePoids >', 5); 
        }
    
        return $builder->get()->getResultArray();
    }
}