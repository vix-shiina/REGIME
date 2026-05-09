<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/dashboard', 'Dashboard::index');
$routes->get('/Dashboard', 'Dashboard::index');
$routes->get('/admin', 'Auth::adminLogin');
$routes->post('/admin', 'Auth::adminLoginPost');
$routes->get('/Admin', static function () {
	return redirect()->to('/admin');
});
$routes->get('/admin-dashboard', static function () {
	$session = service('session');
	$userId = $session->get('user_id');

	if (empty($userId)) {
		return redirect()->to('/admin');
	}

	try {
		$db = db_connect();
		$user = $db->table('USER')
			->select('UserTypeId')
			->where('Id', (int) $userId)
			->get()
			->getRowArray();
	} catch (\Throwable $e) {
		return redirect()->to('/admin');
	}

	if (!$user || (int) $user['UserTypeId'] !== 2) {
		return redirect()->to('/admin');
	}

	return view('Admin/Dashboard');
});
$routes->get('/SignIn', 'Auth::signin');
$routes->post('/SignIn', 'Auth::signinPost');
$routes->get('/SignUp', 'Auth::signup');
$routes->post('/SignUp', 'Auth::signupPost');
$routes->get('/logout', 'Auth::logout');
?>