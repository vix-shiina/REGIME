<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/dashboard', 'Dashboard::index');
$routes->get('/Dashboard', 'Dashboard::index');
$routes->get('/SignIn', 'Auth::signin');
$routes->post('/SignIn', 'Auth::signinPost');
$routes->get('/SignUp', 'Auth::signup');
$routes->post('/SignUp', 'Auth::signupPost');
