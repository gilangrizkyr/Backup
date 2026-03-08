<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/login', 'LoginController::index');
$routes->post('/login', 'LoginController::auth');
$routes->get('/logout', 'LoginController::logout');

$routes->group('backup', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'BackupController::index');
    $routes->get('run', 'BackupController::backup');
    $routes->get('download/(:num)', 'BackupController::download/$1');
    $routes->post('manual', 'BackupController::manualBackup');
});
