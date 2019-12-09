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

class UserChartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $desde = $request->filtro_fecha_desde;
        $hasta = $request->filtro_fecha_hasta;

        $inscripciones = new UserChart;

        $ins = Inscripcion::where('comedor_id', backpack_user()->persona->comedor_id)
        ->when($desde, function($query,$desde){
            return $query->where('fecha_inscripcion','>=',$desde);
        })
        ->when($hasta, function($query,$hasta){
            return $query->where('fecha_inscripcion','<=',$hasta);
        })
        ->get()
        ->groupBy('fecha_inscripcion')
        ->map(function ($item) {
            // Return the number of persons with that age
            return count($item);
        });

        $as = Asistencia::where('comedor_id', backpack_user()->persona->comedor_id)
        ->where('asistio',true)
        ->when($desde, function($query) use($desde){
            return $query->whereHas('inscripcion', function (EloquentBuilder $query) use($desde){
                $query->where('fecha_inscripcion','>=',$desde);
            });
        })
        ->when($hasta, function($query) use($hasta){
            return $query->whereHas('inscripcion', function (EloquentBuilder $query)use($hasta){
                $query->where('fecha_inscripcion','<=',$hasta);
            });
        })
        ->get()
        ->groupBy('inscripcion.fecha_inscripcion')
        ->map(function ($item) {
            // Return the number of persons with that age
            return count($item);
        });

        $inas = Asistencia::where('comedor_id', backpack_user()->persona->comedor_id)
        ->where('asistio',false)
        ->when($desde, function($query) use($desde){
            return $query->whereHas('inscripcion', function (EloquentBuilder $query) use($desde){
                $query->where('fecha_inscripcion','>=',$desde);
            });
        })
        ->when($hasta, function($query) use($hasta){
            return $query->whereHas('inscripcion', function (EloquentBuilder $query)use($hasta){
                $query->where('fecha_inscripcion','<=',$hasta);
            });
        })
        ->get()
        ->groupBy('inscripcion.fecha_inscripcion')
        ->map(function ($item) {
            // Return the number of persons with that age
            return count($item);
        });
    
        $inscripciones->labels($ins->keys());
        $inscripciones->dataset('Inscripciones por dia', 'bar', $ins->values())->color('red');
        $inscripciones->dataset('Asistencias por dia', 'bar', $as->values())->color('green');
        $inscripciones->dataset('Insistencias por dia', 'bar', $inas->values())->color('blue');
        return view('personalizadas.estadisticas', ['inscripciones' => $inscripciones]);
    }
}
