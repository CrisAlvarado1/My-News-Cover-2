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
$routes->get('admin/index', 'Category::index', ['filter' => 'admin']);
$routes->get('admin/create', 'Category::create', ['filter' => 'admin']);
$routes->post('admin/save', 'Category::save', ['filter' => 'admin']);
$routes->get('admin/edit/(:num)', 'Category::edit/$1', ['filter' => 'admin']);
$routes->get('admin/delete/(:num)', 'Category::delete/$1', ['filter' => 'admin']);

// Routes for the "Portada"
$routes->get('users/news/index', 'News::index');
$routes->get('users/news/index/(:num)', 'News::filterNewsByCategory/$1');
$routes->get('users/news/index/tags', 'News::filterNewsByTagsInAllNews');
$routes->get('users/news/index/tags/(:num)', 'News::filterNewsByTagsInCategoryNews/$1');
$routes->get('users/news/public', 'News::publicCover');
$routes->post('users/news/public', 'News::makePublicCover');

// Routes for the search news in "Portada"
$routes->post('users/news/index/search', 'News::searchInAllNews');
$routes->post('users/news/index/search/(:num)', 'News::searchInCategoryNews/$1');

// Routes for the news sources
$routes->get('users/newsSources/index', 'NewsSources::index');
$routes->get('users/newsSources/create', 'NewsSources::create');
$routes->post('users/newsSources/save', 'NewsSources::save');
$routes->get('users/newsSources/edit/(:num)', 'NewsSources::edit/$1');
$routes->get('users/newsSources/delete/(:num)', 'NewsSources::delete/$1');
