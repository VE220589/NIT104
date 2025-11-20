<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/dashboard', 'Dashboard::index'); // Página de login (no protegida)
$routes->post('/api/auth/login', 'Auth::login'); // API de login (no protegida)
$routes->get('/api/auth/exists', 'Auth::exists'); // API para verificar usuarios (no protegida)
$routes->get('/api/auth/logOut', 'Auth::logOut'); // API de logout (puedes protegerla si quieres, pero no es necesario)

// Rutas protegidas (requieren login)
$routes->get('/main', 'Main::main', ['filter' => 'auth']); // Página del dashboard protegida
$routes->get('/usuarios1', 'Usuarios1::usuarios1', ['filter' => 'auth']);
// Agrega más rutas protegidas aquí, por ejemplo:
// $routes->get('/perfil', 'Usuario::perfil', ['filter' => 'auth']);

$routes->group('api/usuarios/', ['namespace' => 'App\Controllers'], function($routes){
    $routes->get('index', 'Usuarios::index');
    $routes->post('readOne', 'Usuarios::readOne');
    $routes->post('create', 'Usuarios::create');
    $routes->post('update', 'Usuarios::update');
    $routes->post('delete', 'Usuarios::delete');
    $routes->post('search', 'Usuarios::search');
    //$routes->get('test', 'Usuarios::test');
    // RUTAS FALTANTES
    $routes->get('getTipo', 'Usuarios::getTipo');
    $routes->get('getEstado', 'Usuarios::getEstado');
});

$routes->get('usuarios/test', 'Usuarios::test');
$routes->get('usuarios/testTable', 'Usuarios::testTable');
$routes->get('usuarios/whichdb', 'Usuarios::whichdb');
$routes->get('usuarios/raw', 'Usuarios::raw');




