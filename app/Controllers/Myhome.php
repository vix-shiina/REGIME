<?php

namespace App\Controllers;

class Myhome extends BaseController
{
    public function index()
    {
        $session = service('session');
        $userId = $session->get('user_id');

        if (empty($userId)) {
            return redirect()->to('/SignIn');
        }

        $userName = $session->get('user_name');

        if (empty($userName)) {
            $db = db_connect();
            $user = $db->table('USER')
                ->select('Prenom')
                ->where('Id', (int) $userId)
                ->get()
                ->getRowArray();

            $userName = $user['Prenom'] ?? 'Utilisateur';
            $session->set('user_name', $userName);
        }

        return view('Myhome', [
            'userName' => $userName,
        ]);
    }
}
