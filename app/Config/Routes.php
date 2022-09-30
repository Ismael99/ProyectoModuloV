<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

$routes->get('/rol', 'Rol::get', ['filter' => 'authFilter']);
$routes->post('/rol', 'Rol::create');
$routes->put('/rol/(:num)', 'Rol::update/$1');
$routes->delete('/rol/(:num)', 'Rol::delete/$1');


$routes->get('/departamento', 'Departamento::get');
$routes->post('/departamento', 'Departamento::create');
$routes->delete('/departamento/(:num)', 'Departamento::delete/$1');


$routes->get('/usuario', 'Usuario::get');
$routes->post('/usuario', 'Usuario::create');
$routes->delete('/usuario/(:num)', 'Usuario::delete/$1');
$routes->post('/usuario/login', 'Usuario::login');


$routes->get('/institucion', 'Institucion::get', ['filter' => 'authFilter']);
$routes->post('/institucion', 'Institucion::create' );
$routes->post('/institucion/(:num)', 'Institucion::update/$1', ['filter' => 'authFilter:Admin']);
$routes->delete('/institucion/(:num)', 'Institucion::delete/$1');


$routes->get('/modalidad', 'Modalidad::get', ['filter' => 'authFilter']);
$routes->post('/modalidad', 'Modalidad::create', ['filter' => 'authFilter:Admin']);
$routes->post('/modalidad/(:num)', 'Modalidad::update/$1', ['filter' => 'authFilter:Admin']);
$routes->delete('/modalidad/(:num)', 'Modalidad::delete/$1', ['filter' => 'authFilter:Admin']);

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
