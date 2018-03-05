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

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->group(['prefix' => 'vendedores'], function() use ($app) {
    $app->get('listed', [
        'as' => 'vendedores/listed',
        'uses' => 'VendedoresController@listed'
    ]);
    $app->get('create', [
        'as' => 'vendedores/create',
        'uses' => 'VendedoresController@create'
    ]);
    $app->post('store', [
        'as' => 'vendedores/store',
        'uses' => 'VendedoresController@store'
    ]);
    $app->get('edit', 'VendedoresController@edit');
    $app->post('update', 'VendedoresController@update');
    $app->get('search_ajax', 'VendedoresController@searchByNameAjax');
});

$app->group(['prefix' => 'socios'], function() use ($app) {
    $app->get('listed', [
        'as' => 'socios/listed',
        'uses' => 'SociosController@listed'
    ]);
    $app->get('create', [
        'as' => 'socios/create',
        'uses' => 'SociosController@create'
    ]);
    $app->post('store', [
        'as' => 'socios/store',
        'uses' => 'SociosController@store'
    ]);
    $app->get('edit', 'SociosController@edit');
    $app->post('update', 'SociosController@update');
    $app->get('search_ajax', 'SociosController@searchByNameAjax');
});