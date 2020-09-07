<?php

namespace App\Http\Controllers\Admin\Operations;

use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Prologue\Alerts\Facades\Alert;

trait InasistenciaFBHOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupInasistenciaFBHRoutes($segment, $routeName, $controller)
    {
        Route::post($segment . '/bulk-noAsistio', [
            'as'        => $routeName . '.inasistenciaFBH',
            'uses'      => $controller . '@inasistenciaFBH',
            'operation' => 'inasistenciafbh',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupInasistenciaFBHDefaults()
    {
        if (backpack_user()->hasRole('operativo')) {

            $this->crud->allowAccess('inasistenciaFBH');

            $this->crud->operation('list', function () {
                $this->crud->enableBulkActions();
                $this->crud->addButtonFromView('bottom', 'inasistenciaFBH', 'inasistenciaFBH', 'end');
            });
        }
    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function inasistenciaFBH()
    {
        $this->crud->hasAccessOrFail('update');

        $entries = $this->crud->getRequest()->input('entries');

        $hoy = Carbon::now();
        $data = collect();
        foreach ($entries as $key => $id) {
            if ($asistencia = $this->crud->model->find($id)) {
                $fi = $asistencia->inscripcion->fecha_inscripcion;
                $fi = Carbon::parse($fi);
                if ($asistencia->asistencia_fbh == false) {
                    $data->push([
                        'status' => false,
                        'message' => 'No se puede asignar una inasistencia a un registro que ya tiene ese estado',
                        'comensal' => $asistencia->inscripcion->user->name,
                        'fecha' => $asistencia->inscripcion->fecha_inscripcion,
                    ]);
                } elseif ($hoy->toDateString() > $fi->toDateString()) {
                    $data->push([
                        'status' => false,
                        'message' => 'No se puede editar una asistencia anterior a la fecha de hoy',
                        'comensal' => $asistencia->inscripcion->user->name,
                        'fecha' => $asistencia->inscripcion->fecha_inscripcion,
                    ]);
                } else {
                    $asistencia->asistencia_fbh = false;
                    $asistencia->fecha_asistencia = null;
                    $asistencia->save();
                    $data->push([
                        'status' => true,
                        'message' => 'Registros Fueron actualizados correctamente',
                        'comensal' => $asistencia->inscripcion->user->name,
                        'fecha' => $asistencia->inscripcion->fecha_inscripcion,
                    ]);
                }
            }
        }
        $data = $data->groupBy([
            'status',
            function ($item) {
                return $item['message'];
            }
        ], $preserveKeys = true)->toArray();
        $data = response()->json($data);
        return $data;
    }
}
