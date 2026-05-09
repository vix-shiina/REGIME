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

    public function createRegime($nom , $typeId, $prix, $efficacite, $viande, $poisson, $volailles)
    {
        $data = [
            'RegimeNom' => $nom,
            'TypeDeRegimeId' => $typeId,
            'PrixJournaliere' => $prix,
            'EfficacitePoids' => $efficacite
        ];
        $this->insert($data);
        $this->db->table('CompoRegime')->insert([
            'RegimeId' => $this->getInsertID(),
            'Viande' => $viande,
            'Poisson' => $poisson,
            'Volailles' => $volailles
        ]);
        return $this->getInsertID();
    }

    public function updateRegime($id, $nom , $typeId, $prix, $efficacite, $viande, $poisson, $volailles)
    {
        $data = [
            'RegimeNom' => $nom,
            'TypeDeRegimeId' => $typeId,
            'PrixJournaliere' => $prix,
            'EfficacitePoids' => $efficacite
        ];
        $this->update($id, $data);
        $this->db->table('CompoRegime')->where('RegimeId', $id)->update([
            'Viande' => $viande,
            'Poisson' => $poisson,
            'Volailles' => $volailles
        ]);
    }

    public function deleteRegime($id)
    {
        $this->db->table('CompoRegime')->where('RegimeId', $id)->delete();
        return $this->delete($id);
    }
}