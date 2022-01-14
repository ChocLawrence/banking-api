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

    $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login');
    
    $router->get('/accounttypes','AccountTypeController@index');
    $router->get('/roles','RoleController@index');


    $router->group(['middleware' => 'auth'], function () use ($router) {

        //logout
        $router->post('/logout', 'AuthController@logout');

        //roles
        $router->post('/roles','RoleController@store');
        $router->put('/roles/{id}', 'RoleController@update');
        $router->delete('/roles/{id}', 'RoleController@destroy');

        //account types
        $router->post('/accounttypes','AccountTypeController@store');
        $router->put('/accounttypes/{id}', 'AccountTypeController@update');
        $router->delete('/accounttypes/{id}', 'AccountTypeController@destroy');


        //User
        $router->get('users', 'UserController@index');
        $router->get('users/{id}', 'UserController@show');
        $router->post('users/search', 'UserController@search');
        $router->put('/users/{id}', 'UserController@update');
        $router->post('/users/change-pass/{id}', 'UserController@changePassword');
        $router->delete('/users/{id}', 'UserController@destroy');

        //account
        $router->get('accounts', 'AccountController@index');
        $router->get('accounts/{id}', 'AccountController@show');
        $router->get('accounts/balance/{id}', 'AccountController@getBalance');
        $router->get('accounts/history/{id}', 'AccountController@history');
        $router->post('accounts', 'AccountController@store');
        $router->put('accounts/{id}', 'AccountController@update');
        $router->delete('accounts/{id}', 'AccountController@destroy');

        //account pins
        $router->get('/pins','PinController@index');
        $router->post('/pins','PinController@store');
        $router->put('/pins/{id}', 'PinController@update');
        $router->delete('/pins/{id}', 'PinController@destroy');
        
        //transaction
        $router->get('transactions', 'TransactionController@index');
        $router->get('transactions/{id}', 'TransactionController@show');
        $router->post('transactions', 'TransactionController@store');

    });
});
