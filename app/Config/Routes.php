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
$routes->post('admin/save', 'Category::save');
$routes->get('admin/edit/(:num)', 'Category::edit/$1');
$routes->get('admin/delete/(:num)', 'Category::delete/$1');

// Routes for the "Portada"
$routes->get('users/news/index', 'News::index');
$routes->get('users/news/index/(:num)', 'News::filterNews/$1');

// Routes for the news sources
$routes->get('users/newsSources/index', 'NewsSources::index');
$routes->get('users/newsSources/create', 'NewsSources::create');
$routes->post('users/newsSources/save', 'NewsSources::save');
$routes->get('users/newsSources/edit/(:num)', 'NewsSources::edit/$1');
$routes->get('users/newsSources/delete/(:num)', 'NewsSources::delete/$1');
