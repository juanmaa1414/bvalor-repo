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
    route('inicio/index');
});

$app->group(['prefix' => 'inicio'], function() use ($app) {
    $app->get('index', [
        'as' => 'inicio/index',
        'uses' => 'InicioController@index'
    ]);
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

$app->group(['prefix' => 'campanias'], function() use ($app) {
    $app->get('listed', [
        'as' => 'campanias/listed',
        'uses' => 'CampaniasController@listed'
    ]);
    $app->get('create', [
        'as' => 'campanias/create',
        'uses' => 'CampaniasController@create'
    ]);
    $app->post('store', [
        'as' => 'campanias/store',
        'uses' => 'CampaniasController@store'
    ]);
    $app->get('edit', 'CampaniasController@edit');
    $app->post('update', 'CampaniasController@update');
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
	$app->get('edit', [
        'as' => 'socios/edit',
        'uses' => 'SociosController@edit'
    ]);
    $app->post('update', 'SociosController@update');
    $app->get('search_ajax', 'SociosController@searchByNameAjax');

	// Familiares
	$app->get('familiares_create', [
        'as' => 'socios/familiares_create',
        'uses' => 'SociosController@familiaresCreate'
    ]);
	$app->post('familiares_store', [
        'as' => 'socios/familiares_store',
        'uses' => 'SociosController@familiaresStore'
    ]);
	$app->get('familiares_edit', 'SociosController@familiaresEdit');
	$app->post('familiares_update', 'SociosController@familiaresUpdate');
});

$app->group(['prefix' => 'planes_abono'], function() use ($app) {
    $app->get('listed', [
        'as' => 'planes_abono/listed',
        'uses' => 'PlanesAbonoController@listed'
    ]);
	$app->get('crear_plan', [
        'as' => 'planes_abono/crear_plan',
        'uses' => 'PlanesAbonoController@crearPlan'
    ]);
	$app->post('grabar_plan', [
        'as' => 'planes_abono/grabar_plan',
        'uses' => 'PlanesAbonoController@grabarPlan'
    ]);
	$app->get('crear_plan_manual', [
        'as' => 'planes_abono/crear_plan_manual',
        'uses' => 'PlanesAbonoController@crearPlanManual'
    ]);
	$app->post('grabar_plan_manual', [
        'as' => 'planes_abono/grabar_plan_manual',
        'uses' => 'PlanesAbonoController@grabarPlanManual'
    ]);
	$app->get('crear_transf_num', [
        'as' => 'planes_abono/crear_transf_num',
        'uses' => 'PlanesAbonoController@crearTransferenciaNumero'
    ]);
});
