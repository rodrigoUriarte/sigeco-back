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
    Route::crud('comensal', 'ComensalCrudController');
    Route::crud('ingreso', 'IngresoCrudController');
    Route::crud('persona', 'PersonaCrudController');
    Route::crud('unidad_academica', 'Unidad_academicaCrudController');
    Route::crud('operativo', 'OperativoCrudController');
}); // this should be the absolute last line of this file