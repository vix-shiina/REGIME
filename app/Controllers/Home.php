<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('SignIn.php');
    }

    public function dashboard(): string
    {
        return view('Dashboard.php');
    }
}
