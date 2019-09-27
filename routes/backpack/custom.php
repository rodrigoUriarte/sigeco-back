<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('comedor', 'ComedorCrudController');
    Route::crud('ingreso', 'IngresoCrudController');
    Route::crud('persona', 'PersonaCrudController');
    Route::crud('unidadAcademica', 'UnidadAcademicaCrudController');
    Route::crud('menu', 'MenuCrudController');
    Route::crud('menuAsignado', 'MenuAsignadoCrudController');
    Route::crud('bandaHoraria', 'BandaHorariaCrudController');
    Route::crud('inscripcion', 'InscripcionCrudController');
}); // this should be the absolute last line of this file