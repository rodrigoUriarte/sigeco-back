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
use App\Models\DiaServicio;
use App\Models\Lote;
use App\Models\Menu;
use App\Models\Plato;
use Carbon\Carbon;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Redirect;
use Prologue\Alerts\Facades\Alert;

class CalculoEstimacionCompra extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dias = DiaServicio::where('comedor_id', backpack_user()->persona->comedor_id)->get();
        $menus = Menu::where('comedor_id', backpack_user()->persona->comedor_id)->get();
        return view('personalizadas.vistaEstimacionCompra', ['dias' => $dias, 'menus' => $menus]);
    }

    public function calculo(SupportCollection $menusCantidad)
    {            
        $mpifc = collect();

        foreach ($menusCantidad as $menu) {

            $cantidad_inscripciones = $menu['cantidad'];

            $menu_id = $menu['menu_id'];

            $platos = Plato::where('comedor_id', backpack_user()->persona->comedor_id)
                ->where('menu_id', $menu_id)
                ->get();

            foreach ($platos as $plato) {
                $insumos_plato = $plato->insumosPlatos;
                $flag = false;

                foreach ($insumos_plato as $insumo_plato) {
                    $cd_insumo = Lote::where('comedor_id', backpack_user()->persona->comedor_id)
                        ->where('insumo_id', $insumo_plato->insumo_id)
                        ->sum('cantidad');
                    $cn_insumo = ($insumo_plato->cantidad * $cantidad_inscripciones);
                    if ($cd_insumo >= $cn_insumo) {
                        //si flag es true 'c' va a ser cantidad que sobra de ese insumo
                        $flag = true;
                        $cs_insumo = $cd_insumo - $cn_insumo;
                    } else {
                        //si flag es flase 'c' va a ser cantidad que falta de ese insumo
                        $flag = false;
                        $cf_insumo = $cn_insumo - $cd_insumo;
                    }

                    $aux = collect();
                    $aux->put('m', $menu_id);
                    $aux->put('p', $plato->id);
                    $aux->put('i', $insumo_plato->id);
                    $aux->put('f', $flag);
                    if ($flag == false) {
                        $aux->put('c', $cf_insumo);
                    }else {
                        $aux->put('c', $cs_insumo);
                    }
                    $mpifc->push($aux->toArray());

                }
            }
        }
        $mpifc = $mpifc->groupBy('m');
        echo ("hola");
        return $mpifc;
    }
    public function reporte(Request $request)
    {
        $dia = $request->filtro_dias;

        $cantidad_semanas = $request->filtro_cantidad_semanas;

        $menus = collect($request->filtro_menu);
        $menus_cantidad = collect($request->filtro_menu_cantidad);

        $flag = false;
        foreach ($menus_cantidad as $mc) {
            if ($mc != null) {
                $flag = true;
            }
        }

        if ($flag == true and $cantidad_semanas != null) {
            Alert::info('Debe completar "Semanas anteriores p/estadistica" o "Cantidad comensales menu, no ambos".')->flash();
            return Redirect::to('admin/calculoEstimacionCompra');
        }

        if ($flag == false and $cantidad_semanas == null) {
            Alert::info('Debe completar "Semanas anteriores p/estadistica" o "Cantidad comensales menu"')->flash();
            return Redirect::to('admin/calculoEstimacionCompra');
        }

        if ($cantidad_semanas != null) {
            //se definio la cantidad de semanas, entonces calculo la estadistica
            switch ($dia) {
                case 'lunes':
                    $day = 'monday';
                    break;
                case 'martes':
                    $day = 'tuesday';
                    break;
                case 'miércoles':
                    $day = 'wednesday';
                    break;
                case 'jueves':
                    $day = 'thursday';
                    break;
                case 'viernes':
                    $day = 'friday';
                    break;
                case 'sábado':
                    $day = 'saturday';
                    break;
                case 'domingo':
                    $day = 'sunday';
                    break;
                default:
                    # code...
                    break;
            }
            $dia = strtotime("next " . $day);
            $cantidadTotal = Collect();
            for ($i = 1; $i <= $cantidad_semanas; $i++) {
                $dia = Carbon::parse($dia);
                $dia = $dia->subWeek();
                $fi = $dia->toDateString();

                $cantidadMenuFecha = Inscripcion::where('comedor_id', backpack_user()->persona->comedor_id)
                    ->whereDate('fecha_inscripcion', $fi)
                    ->with('menuAsignado')
                    ->get();

                $cantidadMenuFecha = $cantidadMenuFecha->groupBy('menuAsignado.menu_id')
                    ->map(function ($group) {
                        return [
                            "menu_id" => $group->pluck('menuAsignado.menu_id')->first(),
                            "cantidad" => $group->count()
                        ];
                    });

                foreach ($cantidadMenuFecha as $cmf) {
                    $cantidadTotal->push($cmf);
                }
            }
            $promedio = $cantidadTotal->groupBy('menu_id')->map(function ($row) {
                return [
                    "menu_id" => $row->pluck('menu_id')->first(),
                    "cantidad" => $row->average('cantidad')
                ];
            });

            if (($promedio->count() == 0)) {
                Alert::info('No se poseen datos para calcular la estadistica, 
                    debera cargarlos manualmente.')->flash();
                return Redirect::to('admin/calculoEstimacionCompra');
            }
            $this->calculo($promedio);

        } elseif ($flag == true) {

            $manual = collect();
            foreach ($menus_cantidad as $key => $value) {
                if ($value != null) {
                    $aux = collect();
                    $aux->put('menu_id', $key);
                    $aux->put('cantidad', $value);
                    $manual->push($aux->toArray());
                }
            }
            $this->calculo($manual);
        }

        // if ($filtro_fecha_vencimiento_desde > $filtro_fecha_vencimiento_hasta) {
        //     Alert::info('El dato "fecha desde" no puede ser mayor a "fecha hasta"')->flash();
        //     return Redirect::to('admin/lote');            
        // }

        // if ($request->filtro_insumo != null) { //aca pregunto si el filtro que viene en el request esta vacio y sino hago el filtro y asi por cada if
        //     foreach ($lotes as $id => $lote) {
        //         if ($lote->insumo->descripcion != $filtro_insumo) {
        //             $lotes->pull($id);
        //         }
        //     }
        // }

        // if ($request->filtro_fecha_vencimiento_desde != null) { //aca pregunto si el filtro que viene en el request esta vacio y sino hago el filtro y asi por cada if
        //     foreach ($lotes as $id => $lote) {
        //         if ($lote->fecha_vencimiento < $filtro_fecha_vencimiento_desde) {
        //             $lotes->pull($id);
        //         }
        //     }
        //     //DESPUES DE USAR EL FILTRO PARA LAS OPERACIONES, PASO EL FILTRO A LA VISTA CON EL FORMATO CORRECTO
        //     $myDate = Date::createFromFormat('Y-m-d', $filtro_fecha_vencimiento_desde);
        //     $filtro_fecha_vencimiento_desde = date_format($myDate, 'd-m-Y');
        // }

        // if ($request->filtro_fecha_vencimiento_hasta != null) { //aca pregunto si el filtro que viene en el request esta vacio y sino hago el filtro y asi por cada if
        //     foreach ($lotes as $id => $lote) {
        //         if ($lote->fecha_vencimiento > $filtro_fecha_vencimiento_hasta) {
        //             $lotes->pull($id);
        //         }
        //     }
        //     //DESPUES DE USAR EL FILTRO PARA LAS OPERACIONES, PASO EL FILTRO A LA VISTA CON EL FORMATO CORRECTO
        //     $myDate2 = Date::createFromFormat('Y-m-d', $filtro_fecha_vencimiento_hasta);
        //     $filtro_fecha_vencimiento_hasta = date_format($myDate2, 'd-m-Y');
        // }

        // if ($request->filtro_lotes_vacios == null) { //aca pregunto si el filtro que viene en el request esta vacio y sino hago el filtro y asi por cada if
        //         foreach ($lotes as $id => $lote) {
        //             if ($lote->cantidad == 0) {
        //                 $lotes->pull($id);
        //             }
        //         }

        // }

        // $pdf = PDF::loadView(
        //     'reportes.reporteLotes',
        //     compact('lotes', 'filtro_insumo', 'filtro_fecha_vencimiento_desde', 'filtro_fecha_vencimiento_hasta', 'filtro_lotes_vacios')
        // );

        // $dom_pdf = $pdf->getDomPDF();
        // $canvas = $dom_pdf->get_canvas();
        // $y = $canvas->get_height() - 15;
        // $pdf->getDomPDF()->get_canvas()->page_text(500, $y, "Pagina {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));

        // $nombre = 'Reporte-Lotes-' . Carbon::now()->format('d/m/Y G:i') . '.pdf';
        // return $pdf->stream($nombre);    
    }
}
