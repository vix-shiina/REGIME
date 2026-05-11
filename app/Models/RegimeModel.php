<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeModel extends Model
{
    protected $table      = 'REGIME';
    protected $primaryKey = 'Id';
    protected $allowedFields = ['RegimeNom', 'TypeDeRegimeId', 'PrixJournaliere', 'EfficacitePoidsParSemaine'];

    
    public function getRegimesWithDetails()
    {
        return $this->select('REGIME.*, CompoRegime.Viande, CompoRegime.Poisson, CompoRegime.Volailles, TypeDeRegime.TypeDeRegime AS TypeNom')
                    ->join('CompoRegime', 'CompoRegime.RegimeId = REGIME.Id', 'left')
                    ->join('TypeDeRegime', 'TypeDeRegime.Id = REGIME.TypeDeRegimeId', 'left')
                    ->findAll();
    }

    public function getRegimeWithDetails($id)
    {
        return $this->select('REGIME.*, CompoRegime.Viande, CompoRegime.Poisson, CompoRegime.Volailles, TypeDeRegime.TypeDeRegime AS TypeNom')
                    ->join('CompoRegime', 'CompoRegime.RegimeId = REGIME.Id', 'left')
                    ->join('TypeDeRegime', 'TypeDeRegime.Id = REGIME.TypeDeRegimeId', 'left')
                    ->where('REGIME.Id', $id)
                    ->first();
    }

    public function createRegime($nom , $typeId, $prix, $efficacite, $viande, $poisson, $volailles)
    {
        $data = [
            'RegimeNom' => $nom,
            'TypeDeRegimeId' => $typeId,
            'PrixJournaliere' => $prix,
            'EfficacitePoidsParSemaine' => $efficacite
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
            'EfficacitePoidsParSemaine' => $efficacite
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

    public function getTypeDeRegimeOptions()
    {
        return $this->db->table('TypeDeRegime')
            ->select('Id, TypeDeRegime')
            ->get()
            ->getResultArray();
    }
}