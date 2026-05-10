<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/dashboard', 'Dashboard::index');

//une route ajoutée!!!!
$routes->post('dashboard/ajouterPoids', 'Dashboard::ajouterPoids'); 
//

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
$routes->get('/logout', 'Auth::logout');

// Pages de gestion (vues) pour l'admin
$routes->get('/admin/regimes/manage', static function () {
	echo view('Admin/GererRegime');
});
$routes->get('/admin/sports/manage', static function () {
	echo view('Admin/GererSport');
});
$routes->get('/admin/clients/manage', static function () {
	echo view('Admin/GererClient');
});
$routes->get('/admin/codes/manage', static function () {
	echo view('Admin/GererCode');
});
//cote admin
$routes->get('/admin/regimes', 'AdminController::regimes');
$routes->post('/admin/regimes/create', 'AdminController::createRegime');
$routes->post('/admin/sports/create', 'AdminController::createSport');
$routes->post('/admin/regimes/delete/(:num)', 'AdminController::deleteRegime/$1');
$routes->post('/admin/sports/delete/(:num)', 'AdminController::deleteSport/$1');
?>