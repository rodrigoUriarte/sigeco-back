<?php

namespace App\Http\Controllers\Admin\Extra;


use App\Models\Inscripcion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DiaServicio;
use App\Models\Insumo;
use App\Models\Lote;
use App\Models\Menu;
use App\Models\Plato;
use Barryvdh\DomPDF\Facade as PDF;
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

        $manual = collect();
        $promedio = collect();

        if ($cantidad_semanas != null) {
            //CALCULO LAS CANTIDADES SEGUN LA ESTADISTICA DE LAS SEMANAS SOLICITADAS POR EL USUARIO
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
        } elseif ($flag == true) {
            //ACA EL USUARIO YA ME DICE LAS CANTIDADES ENTONCES INSERTO ESAS CANTIDADES
            //EN UNA COLECCION QUE DESPUES SE USARA PARA EL CALCULO DE LOS INSUMOS A COMPRAR
            foreach ($menus_cantidad as $key => $value) {
                if ($value != null) {
                    $aux = collect();
                    $aux->put('menu_id', $key);
                    $aux->put('cantidad', $value);
                    $manual->push($aux->toArray());
                }
            }
        }

        //FUNCION DE CALCULO DE LOS INSUMOS A COMPRAR SEGUN LAS CANTIDADES
        //QUE PROVIENEN DE LA ESTADISTICA($PROMEDIO) O DE LA CARGA MANUAL($MANUAL)
        if ($promedio->isNotEmpty()) {
            $menusCantidad = $promedio;
        } elseif ($manual->isNotEmpty()) {
            $menusCantidad = $manual;
        }

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
                        //si flag es false 'c' va a ser cantidad que falta de ese insumo
                        $flag = false;
                        $cf_insumo = $cn_insumo - $cd_insumo;
                    }

                    $mid = $menu_id;
                    $pid = $plato->id;
                    $iid = $insumo_plato->id;
                    $aux = collect();
                    $aux->put('menu', Menu::find($menu_id)->descripcion);
                    $aux->put('plato', Plato::find($plato->id)->descripcion);
                    $aux->put('insumo', Insumo::find($insumo_plato->insumo->id)->descripcion);
                    $aux->put('estado', $flag);
                    if ($flag == false) {
                        $aux->put('cantidad', $cf_insumo .' '. Insumo::find($insumo_plato->insumo->id)->unidad_medida);
                    } else {
                        $aux->put('cantidad', $cs_insumo .' '. Insumo::find($insumo_plato->insumo->id)->unidad_medida);
                    }
                    $mpifc->push($aux);
                }
            }
        }

        $mpifc = $mpifc->groupBy([
            'menu',
            function ($item) {
                return $item['plato'];
            },    function ($item) {
                return $item['insumo'];
            },
        ], $preserveKeys = true);

        //FUNCION DE IMPRESION DEL PDF
        $pdf = PDF::loadView(
            'reportes.reporteEstimacionCompra',
            compact('mpifc')
        );

        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $y = $canvas->get_height() - 15;
        $pdf->getDomPDF()->get_canvas()->page_text(500, $y, "Pagina {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));

        $nombre = 'Reporte-Estimacion-Compra-' . Carbon::now()->format('d/m/Y G:i') . '.pdf';
        return $pdf->stream($nombre);
    }

    // public function calculo(SupportCollection $menusCantidad)
    // {
    //     $mpifc = collect();

    //     foreach ($menusCantidad as $menu) {

    //         $cantidad_inscripciones = $menu['cantidad'];

    //         $menu_id = $menu['menu_id'];

    //         $platos = Plato::where('comedor_id', backpack_user()->persona->comedor_id)
    //             ->where('menu_id', $menu_id)
    //             ->get();

    //         foreach ($platos as $plato) {
    //             $insumos_plato = $plato->insumosPlatos;
    //             $flag = false;

    //             foreach ($insumos_plato as $insumo_plato) {
    //                 $cd_insumo = Lote::where('comedor_id', backpack_user()->persona->comedor_id)
    //                     ->where('insumo_id', $insumo_plato->insumo_id)
    //                     ->sum('cantidad');
    //                 $cn_insumo = ($insumo_plato->cantidad * $cantidad_inscripciones);
    //                 if ($cd_insumo >= $cn_insumo) {
    //                     //si flag es true 'c' va a ser cantidad que sobra de ese insumo
    //                     $flag = true;
    //                     $cs_insumo = $cd_insumo - $cn_insumo;
    //                 } else {
    //                     //si flag es flase 'c' va a ser cantidad que falta de ese insumo
    //                     $flag = false;
    //                     $cf_insumo = $cn_insumo - $cd_insumo;
    //                 }

    //                 $mid=$menu_id;
    //                 $pid=$plato->id;
    //                 $iid=$insumo_plato->id;
    //                 $aux = collect();
    //                 $aux->put('menu', Menu::find($menu_id)->descripcion);
    //                 $aux->put('plato', Plato::find($plato->id)->descripcion);
    //                 $aux->put('insumo', Insumo::find($insumo_plato->insumo->id)->descripcion);
    //                 $aux->put('estado', $flag);
    //                 if ($flag == false) {
    //                     $aux->put('cantidad', $cf_insumo);
    //                 } else {
    //                     $aux->put('cantidad', $cs_insumo);
    //                 }
    //                 $mpifc->push($aux->toArray());
    //             }
    //         }
    //     }
    //     $mpifc = $mpifc->groupBy([
    //         'menu',
    //         function ($item) {
    //             return $item['plato'];
    //         },    function ($item) {
    //             return $item['insumo'];
    //         },
    //     ], $preserveKeys = true);

    //     $this->imprimirReporte($mpifc);
    // }

    // public function imprimirReporte(SupportCollection $mpifc)
    // {

    //     $pdf = PDF::loadView(
    //         'reportes.reporteEstimacionCompra'
    //     );

    //     $dom_pdf = $pdf->getDomPDF();
    //     $canvas = $dom_pdf->get_canvas();
    //     $y = $canvas->get_height() - 15;
    //     $pdf->getDomPDF()->get_canvas()->page_text(500, $y, "Pagina {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));

    //     $nombre = 'Reporte-Estimacion-Compra-' . Carbon::now()->format('d/m/Y G:i') . '.pdf';
    //     return $pdf->stream($nombre);
    // }
}
