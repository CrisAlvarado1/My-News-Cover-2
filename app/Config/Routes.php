<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Routes for the index (login)
$routes->get('/', 'Home::index');
$routes->post('user/authenticate', 'User::authenticate');

// Routes for the sign up
$routes->get('users/index', 'User::index');
$routes->post('users/index/(:num)', 'User::store/$1');