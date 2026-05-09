<?php

namespace App\Models;

use CodeIgniter\Model;

class EvolutionModel extends Model
{
    protected $table      = 'Evolution'; 
    protected $primaryKey = 'Id';

    protected $useAutoIncrement = true;
    protected $returnType     = 'array';

    
    protected $allowedFields = ['UserId', 'DateEvolution', 'Poids'];

    
    public function getEvolutionByUser($userId)
    {
        return $this->where('UserId', $userId)
                    ->orderBy('DateEvolution', 'ASC')
                    ->findAll();
    }
}