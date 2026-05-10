<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/dashboard', 'Dashboard::index');
// $routes->get('/Dashboard', 'Dashboard::index');
$routes->get('/admin', 'Auth::adminLogin');
$routes->post('/admin', 'Auth::adminLoginPost');
$routes->get('/Admin', static function () {
	return redirect()->to('/admin');
});
$routes->get('/admin-dashboard', 'Auth::adminDashboard');
$routes->get('/SignIn', 'Auth::signin');
$routes->post('/SignIn', 'Auth::signinPost');
$routes->get('/SignUp', 'Auth::signup');
$routes->post('/SignUp', 'Auth::signupPost');
$routes->get('/myhome', 'Myhome::index');
$routes->get('/profil', 'Profil::index');
$routes->post('/profil', 'Profil::update');
$routes->get('/profil/solde', 'Profil::addSolde');
$routes->post('/profil/solde', 'Profil::addSoldePost');
$routes->post('/profil/solde/check', 'Profil::checkSoldeCode');
$routes->get('/regime', 'Regime::index');
$routes->get('/regime/create', 'Regime::create');
$routes->post('/regime/create', 'Regime::store');
$routes->get('/logout', 'Auth::logout');
?>