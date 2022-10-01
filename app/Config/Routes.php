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
$routes->post('/rol', 'Rol::create', ['filter' => 'authFilter:Admin']);
$routes->post('/rol/(:num)', 'Rol::update/$1', ['filter' => 'authFilter:Admin']);
$routes->delete('/rol/(:num)', 'Rol::delete/$1', ['filter' => 'authFilter:Admin']);


$routes->get('/departamento', 'Departamento::get', ['filter' => 'authFilter']);
$routes->post('/departamento', 'Departamento::create', ['filter' => 'authFilter:Admin']);
$routes->post('/departamento/(:num)', 'Departamento::update/$1', ['filter' => 'authFilter:Admin']);
$routes->delete('/departamento/(:num)', 'Departamento::delete/$1', ['filter' => 'authFilter:Admin']);


$routes->get('/usuario', 'Usuario::get');
$routes->post('/usuario', 'Usuario::create');
$routes->post('/usuario/(:num)', 'Usuario::update/$1');
$routes->delete('/usuario/(:num)', 'Usuario::delete/$1');
$routes->post('/usuario/login', 'Usuario::login');

$routes->get('/usuario-mision', 'UsuarioMision::index');
$routes->get('/usuario-mision/(usuario|mision)/(:num)', 'UsuarioMision::index/$1/$2');
$routes->post('/usuario-mision', 'UsuarioMision::create');
$routes->delete('/usuario/(:num)/mision/(:num)', 'UsuarioMision::delete/$1/$2');
// $routes->get('/usuario/(:num)/mision/(:num)', 'UsuarioMision::index/$1/$2');

$routes->get('/usuario-capacitacion', 'UsuarioCapacitacion::index');
$routes->get('/usuario-capacitacion/(usuario|capacitacion)/(:num)', 'UsuarioCapacitacion::index/$1/$2');
$routes->post('/usuario-capacitacion', 'UsuarioCapacitacion::create');
$routes->delete('/usuario/(:num)/capacitacion/(:num)', 'UsuarioCapacitacion::delete/$1/$2');

$routes->get('/mision-fecha', 'MisionFechas::index');
$routes->post('/mision-fecha', 'MisionFechas::create');
$routes->post('/mision-fecha/(:num)', 'MisionFechas::update/$1');
$routes->delete('/mision-fecha/(:num)', 'MisionFechas::delete/$1');

$routes->get('/capacitacion-fecha', 'CapacitacionFechas::index');
$routes->post('/capacitacion-fecha', 'CapacitacionFechas::create');
$routes->post('/capacitacion-fecha/(:num)', 'CapacitacionFechas::update/$1');
$routes->delete('/capacitacion-fecha/(:num)', 'CapacitacionFechas::delete/$1');


$routes->get('/mision', 'Mision::index');
$routes->post('/mision', 'Mision::create');
$routes->post('/mision/(:num)', 'Mision::update/$1');
$routes->get('/mision/(:num)', 'Mision::index/$1');

$routes->get('/capacitacion', 'Capacitacion::index');
$routes->post('/capacitacion', 'Capacitacion::create');
$routes->post('/capacitacion/(:num)', 'Capacitacion::update/$1');
$routes->get('/capacitacion/(:num)', 'Capacitacion::index/$1');

$routes->get('/institucion', 'Institucion::get', ['filter' => 'authFilter']);
$routes->post('/institucion', 'Institucion::create', ['filter' => 'authFilter:Admin']);
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
