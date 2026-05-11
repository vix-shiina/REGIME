<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/dashboard', 'Dashboard::index');
$routes->post('/dashboard/select-regime', 'Dashboard::selectRegime');
$routes->post('/dashboard/select-regime/(:num)', 'Dashboard::selectRegime/$1');
$routes->post('/dashboard/select-sport', 'Dashboard::selectSport');
$routes->post('/dashboard/select-sport/(:num)', 'Dashboard::selectSport/$1');

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
$routes->get('/regime', 'Regime::index');
$routes->get('regime', 'Regime::index');
$routes->get('/regime/create', 'Regime::create');
$routes->get('regime/create', 'Regime::create');
$routes->post('/regime/create', 'Regime::store');
$routes->post('regime/create', 'Regime::store');

// Pages de gestion (vues) pour l'admin
$routes->get('/admin/regimes/manage', 'AdminController::manageRegimes');
$routes->get('/admin/sports/manage', 'AdminController::manageSports');
$routes->get('/admin/clients/manage', 'AdminController::manageClients');
$routes->get('/admin/codes/manage', 'AdminController::manageCodes');
//cote admin
$routes->get('/admin/regimes', 'AdminController::manageRegimes');
$routes->get('/admin/sports', 'AdminController::manageSports');
$routes->get('/admin/clients', 'AdminController::manageClients');
$routes->get('/admin/codes', 'AdminController::manageCodes');
$routes->post('/admin/regimes/create', 'AdminController::createRegime');
$routes->post('/admin/sports/create', 'AdminController::createSport');
$routes->post('/admin/codes/create', 'AdminController::createCode');
$routes->get('/admin/sports/edit/(:num)', 'AdminController::editSport/$1');
$routes->post('/admin/sports/update/(:num)', 'AdminController::updateSport/$1');
$routes->get('/admin/codes/edit/(:num)', 'AdminController::editCode/$1');
$routes->post('/admin/codes/update/(:num)', 'AdminController::updateCode/$1');
$routes->post('/admin/regimes/delete/(:num)', 'AdminController::deleteRegime/$1');
$routes->post('/admin/sports/delete/(:num)', 'AdminController::deleteSport/$1');
$routes->post('/admin/codes/delete/(:num)', 'AdminController::deleteCode/$1');
$routes->get('/admin/regimes/edit/(:num)', 'AdminController::editRegime/$1');
$routes->post('/admin/regimes/update/(:num)', 'AdminController::updateRegime/$1');
?>