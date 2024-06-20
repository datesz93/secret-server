<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->group('', ['filter' => 'method'], function($routes) {
    $routes->post('secret', '\App\Controllers\SecretController::create');
    $routes->get('secret/(:segment)', '\App\Controllers\SecretController::show/$1');
});
