<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Routes for the index (login)
$routes->get('/', 'Home::index');
$routes->post('user/authenticate', 'Auth::authenticate');

// Route for logout
$routes->get('logout', 'Auth::logout');

// Routes for the sign up
$routes->get('users/index', 'User::index');
$routes->post('users/index/(:num)', 'User::store/$1');

// Routes for the categories (admin area)
$routes->get('admin/index', 'Category::index');
$routes->get('admin/create', 'Category::create');
$routes->get('admin/edit/(:num)', 'Category::edit/$1');
$routes->get('admin/delete/(:num)', 'Category::delete/$1');
$routes->post('admin/save', 'Category::save');
