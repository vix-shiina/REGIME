<?php
    namespace App\Models;
    use CodeIgniter\Model;

    class ClientModel extends Model
    {
        protected $table = 'USER';
        protected $primaryKey = 'Id';

        protected $allowedFields = ['Nom', 'Prenom', 'Email', 'Password', 'UserTypeId', 'GenreId'];

        public function getClientsWithCurrentRegime(?string $search = null, int $limit = 20, int $offset = 0): array
        {
            $builder = $this->db->table('USER u');
            $builder->select('u.Id, u.Nom, u.Prenom, u.Email, u.UserTypeId, ut.UserType, gt.Genre, ui.Age, ui.Taille, ui.Poids, ru.DateDebut, ru.DureeEnJours, r.RegimeNom')
                ->join('UserType ut', 'ut.Id = u.UserTypeId', 'left')
                ->join('Genre gt', 'gt.Id = u.GenreId', 'left')
                ->join('UserInfo ui', 'ui.UserId = u.Id', 'left')
                ->join(
                    '(SELECT ru1.* FROM RegimeUser ru1 INNER JOIN (SELECT UserId, MAX(DateDebut) AS MaxDateDebut FROM RegimeUser GROUP BY UserId) latest ON latest.UserId = ru1.UserId AND latest.MaxDateDebut = ru1.DateDebut) ru',
                    'ru.UserId = u.Id',
                    'left',
                    false
                )
                ->join('REGIME r', 'r.Id = ru.RegimeId', 'left');

            if ($search !== null && trim($search) !== '') {
                $builder->groupStart()
                    ->like('u.Nom', $search)
                    ->orLike('u.Prenom', $search)
                    ->orLike('u.Email', $search)
                    ->groupEnd();
            }

            $builder->orderBy('u.Id', 'DESC')
                ->limit($limit, $offset);

            return $builder->get()->getResultArray();
        }

        public function countClients(?string $search = null): int
        {
            $builder = $this->db->table('USER u');

            if ($search !== null && trim($search) !== '') {
                $builder->groupStart()
                    ->like('u.Nom', $search)
                    ->orLike('u.Prenom', $search)
                    ->orLike('u.Email', $search)
                    ->groupEnd();
            }

            return (int) $builder->countAllResults();
        }
    }

?>