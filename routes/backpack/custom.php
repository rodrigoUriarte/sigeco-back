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
    Route::crud('persona', 'PersonaCrudController');
    Route::crud('unidadAcademica', 'UnidadAcademicaCrudController');
    Route::crud('menu', 'MenuCrudController');
    Route::crud('menuAsignado', 'MenuAsignadoCrudController');
    Route::crud('bandaHoraria', 'BandaHorariaCrudController');
    Route::crud('inscripcion', 'InscripcionCrudController');
    Route::crud('plato', 'PlatoCrudController');
    Route::crud('insumo', 'InsumoCrudController');
    Route::crud('insumoPlato', 'InsumoPlatoCrudController');
    Route::crud('lote', 'LoteCrudController');
    Route::crud('ingresoInsumo', 'IngresoInsumoCrudController');
    Route::crud('platoAsignado', 'PlatoAsignadoCrudController');
    Route::crud('asistencia', 'AsistenciaCrudController');
    Route::crud('regla', 'ReglaCrudController');
    Route::crud('sancion', 'SancionCrudController');
    Route::crud('user', 'Extra\CustomUserCrudController');
    Route::crud('auditoria', 'AuditoriaCrudController');
    Route::crud('parametro', 'ParametroCrudController');
    Route::crud('justificacion', 'JustificacionCrudController');

    Route::get('/calculoPreparacionPlatos', 'Extra\CalculoPreparacionPlatos@index');
    Route::get('/calculoPreparacionPlatos/{id}', 'Extra\CalculoPreparacionPlatos@index');

    Route::get('/calculoInasistenciasFecha', 'Extra\CalculoInasistenciasFecha@index');
    Route::get('/calculoInasistenciasFecha/{id}', 'Extra\CalculoInasistenciasFecha@index');

    Route::get('/reporteLotes', 'LoteCrudController@reporteLotes')->name('lotes.reporteLotes');
    Route::get('/reporteInscripciones', 'InscripcionCrudController@reporteInscripciones')->name('inscripciones.reporteInscripciones');

    Route::get('/estadisticas', 'Extra\UserChartController@index')->name('estadisticas');

    Route::get('/ayuda', 'Extra\AyudaController@index')->name('ayuda');

});