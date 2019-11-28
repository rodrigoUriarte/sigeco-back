<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Inscripcion;
use App\Models\Lote;
use Illuminate\Http\Request;
use App\Models\Plato;
use Facade\Ignition\DumpRecorder\Dump;

class PlatoController extends Controller
{

    public function platosDisponibles(Request $request)
    {
        $form = collect($request->input('form'))->pluck('value', 'name');

        $fecha = $form['fecha'];
        $comedor = $form['comedor_id'];
        $menu = $form['menu_id'];

        $cantidad_inscripciones = Inscripcion::where('fecha_inscripcion', $fecha)
            ->where('comedor_id', $comedor)
            ->whereHas('menuAsignado',  function ($q) use ($menu) {
                $q->where('menu_id', $menu);
            })
            ->count();

        $platos = Plato::where('comedor_id', $comedor)
            ->where('menu_id', $menu)
            ->get();


        $pd = collect();

        foreach ($platos as $plato) {
            $insumos_plato = $plato->insumosPlatos;
            $flag = false;

            foreach ($insumos_plato as $insumo_plato) {
                //$flag = true;
                $cd_insumo = Lote::where('comedor_id', $comedor)
                    ->where('insumo_id', $insumo_plato->insumo_id)
                    ->sum('cantidad');
                $cn_insumo = ($insumo_plato->cantidad * $cantidad_inscripciones);
                if ($cd_insumo >= $cn_insumo) {
                    $flag = true;
                    //break;
                } else {
                    $flag = false;
                    break;
                }
            }

            if ($flag == true) {
                $pd->push($plato);
            }
        }
        return $pd;
    }

    public function index(Request $request)
    {

        // $pd = $this->platosDisponibles($request);

        $search_term = $request->input('q');

        // $page = $request->input('page');

        $form = collect($request->input('form'))->pluck('value', 'name');

        $options = collect();

        // if no category has been selected, show no options
        if (!$form['menu_id']) {
            return [];
        }

        // if a category has been selected, only show articles in that category
        if ($form['menu_id']) {
            $options = $this->platosDisponibles($request);
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


        // //FUNCION QUE ANDA
        // $search_term = $request->input('q');

        // $form = collect($request->input('form'))->pluck('value', 'name');

        // $options = Plato::query();

        // // if no category has been selected, show no options
        // if (!$form['menu_id']) {
        //     return [];
        // }

        // // if a category has been selected, only show articles in that category
        // if ($form['menu_id']) {
        //     $options = $options->where('menu_id', $form['menu_id']);
        // }

        // if ($search_term) {
        //     $results = $options->where('descripcion', 'LIKE', '%' . $search_term . '%')->paginate(10);
        // } else {
        //     $results = $options->paginate(10);
        // }

        // $options=$options->paginate(10);
        // return $options;
    }


    public function show($id)
    {
        return Category::find($id);
    }
}
