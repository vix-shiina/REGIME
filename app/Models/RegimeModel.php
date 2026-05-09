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
}