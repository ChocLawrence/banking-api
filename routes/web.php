<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->group(['prefix'=>'api'], function () use ($router) {
    $router->get('/accounttypes','AccountTypeController@index');
    $router->post('/accounttypes','AccountTypeController@store');
    $router->put('/accounttypes/{id}', 'AccountTypeController@update');
    $router->delete('/accounttypes/{id}', 'AccountTypeController@destroy');

    $router->get('/roles','RoleController@index');
    $router->post('/roles','RoleController@store');
    $router->put('/roles/{id}', 'RoleController@update');
    $router->delete('/roles/{id}', 'RoleController@destroy');
});
