<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Authors routes
$routes->get('authors', 'AuthorController::index');
$routes->post('authors/create', 'AuthorController::create');
$routes->get('authors/edit/(:num)', 'AuthorController::edit/$1');
$routes->post('authors/edit/(:num)', 'AuthorController::edit/$1');
$routes->get('authors/delete/(:num)', 'AuthorController::delete/$1');

// Subjects routes
$routes->get('subjects', 'SubjectController::index');
$routes->post('subjects/create', 'SubjectController::create');
$routes->get('subjects/edit/(:num)', 'SubjectController::edit/$1');
$routes->post('subjects/edit/(:num)', 'SubjectController::edit/$1');
$routes->get('subjects/delete/(:num)', 'SubjectController::delete/$1');

// Books routes
$routes->get('books', 'BookController::index');
$routes->post('books/create', 'BookController::create');
$routes->get('books/edit/(:num)', 'BookController::edit/$1');
$routes->post('books/edit/(:num)', 'BookController::edit/$1');
$routes->get('books/delete/(:num)', 'BookController::delete/$1');
$routes->get('books/getBook/(:num)', 'BookController::getBook/$1');
$routes->match(['get', 'post'], 'books/errorMsg', 'BookController::errorMsg');

// Reports routes
$routes->get('reports', 'ReportController::index');
$routes->get('reports/exportExcel', 'ReportController::exportExcel');
