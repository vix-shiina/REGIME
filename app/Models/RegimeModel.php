<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeModel extends Model
{
    protected $table      = 'REGIME';
    protected $primaryKey = 'Id';
    protected $allowedFields = ['RegimeNom', 'TypeDeRegimeId', 'PrixJournaliere', 'EfficacitePoids'];

    
    public function getRegimesWithDetails()
    {
        return $this->select('REGIME.*, CompoRegime.Viande, CompoRegime.Poisson, CompoRegime.Volailles')
                    ->join('CompoRegime', 'CompoRegime.RegimeId = REGIME.Id')
                    ->findAll();
    }

        public function getRegimesParTypesMultiples($idTypes)
    {
        $builder = $this->db->table('REGIME r');
        $builder->select('r.*, c.Viande, c.Poisson, c.Volailles');
        $builder->join('CompoRegime c', 'r.Id = c.RegimeId', 'left');
    
        if (is_array($idTypes)) {
            $builder->whereIn('r.TypeDeRegimeId', $idTypes);
        } else {
            $builder->where('r.TypeDeRegimeId', $idTypes);
        }
    
        return $builder->get()->getResultArray();
    }
}