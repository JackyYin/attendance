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
    $router->group(['middleware' => 'custom-jwt-agent'], function () use ($router) {
        $router->post('login', ['uses' => 'AuthController@authenticate']);
        $router->post('logout', ['uses' => 'AuthController@logout']);
        $router->post('refresh', ['uses' => 'AuthController@refresh']);
    });

    $router->group(['middleware' => 'custom-jwt:user'], function () use ($router) {
        $router->get('me', ['uses' => 'AuthController@me']);
    });
});

$router->group(['middleware' => 'custom-jwt:user'], function () use ($router) {
    $router->group(['prefix' => 'attendances'], function () use ($router) {
        $router->post('gps', ['uses' => 'AttendanceController@gps']);
        $router->post('wifi', ['uses' => 'AttendanceController@wifi']);
    });
});

