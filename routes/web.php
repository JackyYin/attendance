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

$router->group(['middleware' => 'custom-jwt:company'], function () use ($router) {
    $router->get('/version', function () use ($router) {
        return $router->app->version();
    });
    $router->group(['prefix' => 'companies', 'as' => 'company'], function () use ($router) {
        $router->get('/me', ['as' => 'me', 'uses' => 'Company\AuthController@me']);
        $router->post('/logout', ['as' => 'logout', 'uses' => 'Company\AuthController@logout']);
        $router->post('/refresh', ['as' => 'refresh', 'uses' => 'Company\AuthController@refresh']);
    });
    $router->group(['prefix' => 'users', 'as' => 'user'], function () use ($router) {
        $router->get('/create', ['as' => 'create', 'uses' => 'UserController@create']);
        $router->post('/', ['as' => 'store', 'uses' => 'UserController@store']);
    });
});

$router->group(['prefix' => 'companies', 'as' => 'company'], function () use ($router) {
    $router->post('/', ['as' => 'store', 'uses' => 'CompanyController@store']);
    $router->post('/authenticate', ['as' => 'authenticate', 'uses' => 'Company\AuthController@authenticate']);
});
