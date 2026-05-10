<?php
    namespace App\Models;

    use CodeIgniter\Model;

    class CodeModel extends Model
    {
        protected $table = 'Code';
        protected $primaryKey = 'Id';
        protected $allowedFields = ['Code', 'Valeur', 'DateExpiration', 'Actif'];

        private function filterPayload(array $data): array
        {
            $availableFields = array_flip($this->db->getFieldNames($this->table));
            return array_intersect_key($data, $availableFields);
        }

        public function getCodes()
        {
            return $this->orderBy('Id', 'ASC')->findAll();
        }

        public function getCodeById($id)
        {
            return $this->find($id);
        }

        public function createCode($code, $valeur, $dateExpiration = null, $actif = 1) {
            $data = $this->filterPayload([
                'Code' => $code,
                'Valeur' => $valeur,
                'DateExpiration' => $dateExpiration,
                'Actif' => $actif
            ]);
            return $this->insert($data);
        }

        public function updateCode($id, $code, $valeur, $dateExpiration = null, $actif = 1)
        {
            $data = $this->filterPayload([
                'Code' => $code,
                'Valeur' => $valeur,
                'DateExpiration' => $dateExpiration,
                'Actif' => $actif
            ]);
            return $this->update($id, $data);
        }

        public function deleteCode($id) {
            return $this->delete($id);
        }
    }

?>