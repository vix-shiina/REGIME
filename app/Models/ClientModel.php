<?php
    namespace App\Models;
    use CodeIgniter\Model;

    class ClientModel extends Model
    {
        protected $table = '';
        protected $primaryKey = 'Id';
        protected $allowedFields = ['Nom', 'Prenom', 'Email', 'MotDePasse', 'PoidsActuel', 'ObjectifPoids', 'Taille', 'Age', 'Sexe'];
    }

?>