<?php
    namespace App\Models;

    use CodeIgniter\Model;

     class CodeModel extends Model
    {
        protected $table = 'CODE';
        protected $primaryKey = 'Id';
        protected $allowedFields = ['Code', 'valeur'];

        public function createCode($code, $valeur) {
            $data = [
                'Code' => $code,
                'valeur' => $valeur
            ];
            return $this->insert($data);
        }

        public function deleteCode($id) {
            return $this->delete($id);
        }
    }

?>