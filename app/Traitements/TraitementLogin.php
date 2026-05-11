<?php
namespace App\Traitements;

class TraitementLogin
{
    protected $pdo;

    public function __construct()
    {
        $dsn = 'mysql:host=127.0.0.1;dbname=REGIME;charset=utf8mb4';
        $this->pdo = new \PDO($dsn, 'root', '', [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    }

    public function signup(array $data)
    {
        $session = service('session');

        $nom = trim($data['nom'] ?? '');
        $prenom = trim($data['prenom'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';
        $genre_id = !empty($data['genre_id']) ? (int)$data['genre_id'] : null;

        if (!$nom || !$prenom || !$email || !$password || !$genre_id) {
            $session->setFlashdata('flash_error', 'Tous les champs sont requis.');
            return redirect()->to('/SignUp');
        }

        $stmt = $this->pdo->prepare('SELECT Id FROM USER WHERE Email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()){
            $session->setFlashdata('flash_error', 'Un compte avec cet email existe déjà.');
            return redirect()->to('/SignUp');
        }

        $ins = $this->pdo->prepare('INSERT INTO USER (Nom,Prenom,Email,Password,GenreId) VALUES (?,?,?,?,?)');
        $ins->execute([$nom,$prenom,$email,$password,$genre_id]);

        $session->setFlashdata('flash_success', 'Inscription réussie. Vous pouvez maintenant vous connecter.');
        return redirect()->to('/SignIn');
    }

    public function signin(array $data)
    {
        return $this->authenticate($data, '/SignIn');
    }

    public function adminSignin(array $data)
    {
        return $this->authenticate($data, '/admin');
    }

    private function authenticate(array $data, string $failureRedirect)
    {
        $session = service('session');

        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';
        if (!$email || !$password){
            $session->setFlashdata('flash_error', 'Tous les champs sont requis.');
            return redirect()->to($failureRedirect);
        }

        $stmt = $this->pdo->prepare('SELECT Id, Nom, Prenom, UserTypeId FROM USER WHERE Email = ? AND Password = ?');
        $stmt->execute([$email,$password]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user) {
            $session->set('user_id', $user['Id']);
            $session->set('user_type_id', (int) $user['UserTypeId']);
            $session->set('user_type', ((int) $user['UserTypeId'] === 2) ? 'admin' : 'client');
            $session->setFlashdata('flash_success', 'Connexion réussie. Bienvenue '.$user['Prenom'].'.');
            if ((int) $user['UserTypeId'] === 2) {
                return redirect()->to('/admin-dashboard');
            }

            return redirect()->to('/myhome');
        }

        $session->setFlashdata('flash_error', 'Email ou mot de passe invalide.');
        return redirect()->to($failureRedirect);
    }
}
