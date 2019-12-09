<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/api/plato', 'Api\PlatoController@index');
// Route::get('/api/plato/{id}', 'Api\PlatoController@show');

// Route::get('/reporteLotes', 'Admin\LoteCrudController@reporteLotes')->name('lotes.reporteLotes');
// Route::get('/reporteInscripciones', 'Admin\InscripcionCrudController@reporteInscripciones')->name('inscripciones.reporteInscripciones');

// Route::get('admin/estadisticas','UserChartController@index')->name('estadisticas');
