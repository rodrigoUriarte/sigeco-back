<?php

namespace App\Http\Controllers\Admin\Extra;

use App\Http\Controllers\Controller;
use App\Models\Inscripcion;
use App\Models\Lote;
use Illuminate\Http\Request;
use App\Models\Plato;
use Facade\Ignition\DumpRecorder\Dump;
use Illuminate\Support\Facades\Redirect;
use Prologue\Alerts\Facades\Alert;

class CalculoPreparacionPlatos extends Controller
{

    public function platosDisponibles(Request $request)
    {
        $form = collect($request->input('form'))->pluck('value', 'name');

        $fecha = $form['fecha'];
        $comedor = $form['comedor_id'];
        $menu = $form['menu_id'];

        $platosDisponibles = collect();

        $cantidad_inscripciones = Inscripcion::where('fecha_inscripcion', $fecha)
            ->where('comedor_id', $comedor)
            ->whereHas('menuAsignado',  function ($q) use ($menu) {
                $q->where('menu_id', $menu);
            })
            ->count();

        //si no hay inscriptos se no se devuelve ningun plato
        //faltaria agregar un mensaje flash avisando que no hay inscriptos para esa fecha
        if ($cantidad_inscripciones == 0) {
            return $platosDisponibles;
        }

        $platos = Plato::where('comedor_id', $comedor)
            ->where('menu_id', $menu)
            ->get();


        foreach ($platos as $plato) {
            $insumos = $plato->insumos;
            $flag = false;

            foreach ($insumos as $insumo) {
                $cd_insumo = Lote::where('comedor_id', $comedor)
                    ->where('insumo_id', $insumo->id)
                    ->sum('cantidad');
                $cn_insumo = ($insumo->pivot->cantidad * $cantidad_inscripciones);
                if ($cd_insumo >= $cn_insumo) {
                    $flag = true;
                } else {
                    $flag = false;
                    break;
                }
            }

            if ($flag == true) {
                $platosDisponibles->push($plato);
            }
        }
        return $platosDisponibles;
    }

    public function index(Request $request)
    {
        $search_term = $request->input('q');

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
            $options = $options->filter(function ($item) use ($search_term) {
                // replace stristr with your choice of matching function
                return false !== stristr($item->descripcion, $search_term);
            })->paginate(10);
        } else {
            $options = $options->paginate(10);
        }

        return $options;
    }

}
