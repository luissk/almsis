<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Inicio');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Inicio::index');
$routes->get('dashboard', 'Inicio::dashboard');
$routes->get('categorias', 'Producto::categorias');
$routes->get('umedida', 'Producto::umedidas');
$routes->get('productos', 'Producto::productos');
$routes->get('nuevo-producto', 'Producto::nuevoProducto');
$routes->get('edit-producto-(:num)', 'Producto::nuevoProducto/$1');
$routes->get('imagen-producto-(:num)', 'Producto::uploadImagen/$1');
$routes->get('delete-image-(:num)', 'Producto::deleteImagen/$1');


$routes->get('kardex', 'Producto::kardex');
$routes->get('detalle-en-excel', 'Producto::detalleExcel');
$routes->get('movimientos-(:num)', 'Producto::movimientos/$1');


$routes->get('entradas', 'Entrada::index');
$routes->get('nueva-entrada', 'Entrada::nuevaEntrada');
$routes->get('edit-entrada-(:num)', 'Entrada::editEntrada/$1');

$routes->get('salidas', 'Salida::index');
$routes->get('areas', 'Salida::areas');
$routes->get('nueva-salida', 'Salida::nuevaSalida');
$routes->get('edit-salida-(:num)', 'Salida::editSalida/$1');

$routes->get('usuarios', 'Usuario::index');
$routes->get('nuevo-usuario', 'Usuario::nuevoUsuario');
$routes->get('edit-usuario-(:num)', 'Usuario::nuevoUsuario/$1');

$routes->get('backup', 'Usuario::backup');
$routes->get('elimina-backup-(:any)', 'Usuario::eliminarBackup/$1');

$routes->get('requerimiento', 'Requerimiento::requerimiento');

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
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
$routes->get('listar', 'Articulos::index');
$routes->get('crear', 'Articulos::crear');
$routes->post('guardar', 'Articulos::guardar');