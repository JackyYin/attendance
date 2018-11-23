<?php
use Illuminate\Http\Request;

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
$router->group(['middleware' => 'admin-authentication'], function () use ($router) {
    $router->get('/',  ['as' => 'index', 'uses' => 'AuthController@index']);
});

$router->get('login',  ['as' => 'login', 'uses' => 'AuthController@login']);
$router->post('authenticate', ['as' => 'authenticate', 'uses' => 'AuthController@authenticate']);
