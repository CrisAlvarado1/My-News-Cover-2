<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Route for the index (login)
$routes->get('/', 'Home::index');

// Routes for the sign up
$routes->get('users/signup', 'User::index');
$routes->post('users/signup/(:num)', 'User::store/$1');