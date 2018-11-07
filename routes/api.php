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

$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('login', ['uses' => 'AuthController@authenticate']);

    $router->group(['middleware' => 'custom-jwt'], function () use ($router) {
        $router->post('logout', ['uses' => 'AuthController@logout']);
        $router->post('refresh', ['uses' => 'AuthController@refresh']);
        $router->get('me', ['uses' => 'AuthController@me']);
    });
});

