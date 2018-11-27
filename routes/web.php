<?php

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

$router->group(['middleware' => 'web-authentication:company'], function () use ($router) {
    $router->get('/version', function () use ($router) {
        return $router->app->version();
    });
    $router->group(['prefix' => 'user', 'as' => 'user'], function () use ($router) {
        $router->get('/create', ['as' => 'create', 'uses' => 'UserController@create']);
        $router->post('/', ['as' => 'store', 'uses' => 'UserController@store']);
    });
});

$router->group(['prefix' => 'company', 'as' => 'company'], function () use ($router) {
    $router->post('/', ['as' => 'store', 'uses' => 'CompanyController@store']);
    $router->post('/authenticate', ['as' => 'authenticate', 'uses' => 'Company\AuthController@authenticate']);
});
