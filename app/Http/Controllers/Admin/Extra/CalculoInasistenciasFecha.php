<?php

namespace App\Http\Controllers\Admin\Extra;

use App\Http\Controllers\Controller;
use App\Models\Asistencia;
use App\Models\Inscripcion;
use App\Models\Lote;
use Illuminate\Http\Request;
use App\Models\Plato;
use Facade\Ignition\DumpRecorder\Dump;

class CalculoInasistenciasFecha extends Controller
{

    public function inasistencias(Request $request)
    {
        $form = collect($request->input('form'))->pluck('value', 'name');

        $fecha_busqueda = $form['fecha_busqueda'];

        $inasistencias = Asistencia::where('comedor_id', backpack_user()->persona->comedor_id)
            ->where('asistio', false)
            ->where('asistencia_fbh', false)
            ->whereHas('inscripcion', function ($query) use ($fecha_busqueda) {
                $query
                    ->where('fecha_inscripcion', $fecha_busqueda);
            })
            ->get();


        $inas = collect();

        foreach ($inasistencias as $in) {
            $inas->push($in);
        }

        return $inas;
    }

    public function index(Request $request)
    {

        $search_term = $request->input('q');

        // $page = $request->input('page');

        $form = collect($request->input('form'))->pluck('value', 'name');

        $options = collect();

        // if no category has been selected, show no options
        if (!$form['fecha_busqueda']) {
            return [];
        }

        // if a category has been selected, only show articles in that category
        if ($form['fecha_busqueda']) {
            $options = $this->inasistencias($request);
        }

        if ($search_term) {

            // $options->filter(function ($item) use ($search_term) {
            //     // replace stristr with your choice of matching function
            //     return false !== stristr($item->descripcion, $search_term);
            // })->paginate(10);

        } else {
            $options = $options->paginate(10);
        }

        return $options;
    }


    // public function show($id)
    // {
    //     return Category::find($id);
    // }
}
