<?php

namespace App\Controllers;

use App\Traitements\TraitementLogin;

class Auth extends BaseController
{
    public function signin()
    {
        session();
        return view('SignIn');
    }

    public function signup()
    {
        session();
        return view('SignUp');
    }

    public function signinPost()
    {
        session();
        $traitement = new TraitementLogin();
        return $traitement->signin($this->request->getPost());
    }

    public function signupPost()
    {
        session();
        $traitement = new TraitementLogin();
        return $traitement->signup($this->request->getPost());
    }

    public function logout()
    {
        session();
        session()->destroy();
        return redirect()->to('/SignIn');
    }

    public function adminLogin()
    {
        session();
        return view('SignInAdmin');
    }

    public function adminLoginPost()
    {
        session();
        $traitement = new TraitementLogin();
        return $traitement->adminSignin($this->request->getPost());
    }
}
