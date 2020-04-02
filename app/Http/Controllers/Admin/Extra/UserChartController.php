<?php

namespace App\Http\Controllers\Admin\Extra;

use App\Charts\UserChart;
use App\Models\Asistencia;
use App\Models\Inscripcion;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Prologue\Alerts\Facades\Alert;

class UserChartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (backpack_user()->hasPermissionTo('verEstadistica')) {
            $desde = $request->filtro_fecha_desde;
            $hasta = $request->filtro_fecha_hasta;

            if ($desde == null && $hasta == null) {
                $vacio = new UserChart;
                $emptyArray = [];
                $vacio->labels($emptyArray);
                $vacio->dataset('Inscripciones por dia', 'line', $emptyArray)->color('blue');
                $vacio->dataset('Asistencias por dia', 'line', $emptyArray)->color('green');
                $vacio->dataset('Insistencias por dia', 'line', $emptyArray)->color('red');
                return view('personalizadas.estadisticas', ['inscripciones' => $vacio]);
            }

            if (($desde > $hasta) and ($desde!=null and $hasta!=null)) {
                Alert::info('El dato "fecha desde" no puede ser mayor a "fecha hasta"')->flash();
                return Redirect::to('admin/estadisticas');
            }

            $inscripciones = new UserChart;

            $ins = Inscripcion::where('comedor_id', backpack_user()->persona->comedor_id)
                ->when($desde, function ($query, $desde) {
                    return $query->where('fecha_inscripcion', '>=', $desde);
                })
                ->when($hasta, function ($query, $hasta) {
                    return $query->where('fecha_inscripcion', '<=', $hasta);
                })
                ->get()
                ->sortBy('fecha_inscripcion')
                ->groupBy('fecha_inscripcion_formato')
                ->map(function ($item) {
                    // Return the number of persons with that age
                    return count($item);
                });

            $as = Asistencia::where('comedor_id', backpack_user()->persona->comedor_id)
                ->where(function ($query) {
                    $query->where('asistio', true)
                        ->orWhere('asistencia_fbh', true);
                })
                ->when($desde, function ($query) use ($desde) {
                    return $query->whereHas('inscripcion', function (EloquentBuilder $query) use ($desde) {
                        $query->where('fecha_inscripcion', '>=', $desde);
                    });
                })
                ->when($hasta, function ($query) use ($hasta) {
                    return $query->whereHas('inscripcion', function (EloquentBuilder $query) use ($hasta) {
                        $query->where('fecha_inscripcion', '<=', $hasta);
                    });
                })
                ->get()
                ->sortBy('inscripcion.fecha_inscripcion')
                ->groupBy('inscripcion.fecha_inscripcion')
                ->map(function ($item) {
                    // Return the number of persons with that age
                    return count($item);
                });

            $inas = Asistencia::where('comedor_id', backpack_user()->persona->comedor_id)
                ->where(function ($query) {
                    $query->where('asistio', false)
                        ->where('asistencia_fbh', false);
                })
                ->when($desde, function ($query) use ($desde) {
                    return $query->whereHas('inscripcion', function (EloquentBuilder $query) use ($desde) {
                        $query->where('fecha_inscripcion', '>=', $desde);
                    });
                })
                ->when($hasta, function ($query) use ($hasta) {
                    return $query->whereHas('inscripcion', function (EloquentBuilder $query) use ($hasta) {
                        $query->where('fecha_inscripcion', '<=', $hasta);
                    });
                })
                ->get()
                ->sortBy('inscripcion.fecha_inscripcion')
                ->groupBy('inscripcion.fecha_inscripcion')
                ->map(function ($item) {
                    // Return the number of persons with that age
                    return count($item);
                });

            $inscripciones->labels($ins->keys());
            $inscripciones->dataset('Inscripciones por dia', 'line', $ins->values())->color('blue');
            $inscripciones->dataset('Asistencias por dia', 'line', $as->values())->color('green');
            $inscripciones->dataset('Insistencias por dia', 'line', $inas->values())->color('red');
            return view('personalizadas.estadisticas', ['inscripciones' => $inscripciones]);
        } else {
            return abort(403, 'Acceso denegado - usted no tiene los permisos necesarios para ver esta página.');
        }
    }
}
