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
    //$router->get('/storage', 'AssetController@getAsset');

$router->group(['prefix' => 'api'], function() use($router){

    $router->get('/assets/{accountId}/{path}/{ext}', 'AssetController@getAsset');

    $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login');
    $router->post('/logout', 'AuthController@logout');
    $router->get('/tree/{accountId}', 'TreeController@getTree');


    $router->group(['middleware' => 'auth'], function () use ($router) {

        $router->post('/asset', 'AssetController@uploadImage');
        $router->post('/tree', 'TreeController@addTree');
        $router->get('/tree-by-id/{accountId}/{id}', 'TreeController@getTreeById');
        $router->get('/tree-versions/{accountId}', 'TreeController@getTreeVersions');

    });



});


