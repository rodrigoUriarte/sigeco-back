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
use DOMDocument;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Redirect;
use Prologue\Alerts\Facades\Alert;
use Mpdf\Mpdf;

class CalculoEstimacionCompra extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (backpack_user()->hasPermissionTo('calcularEstimacionCompra')) {
            $dias = DiaServicio::where('comedor_id', backpack_user()->persona->comedor_id)->get();
            $menus = Menu::where('comedor_id', backpack_user()->persona->comedor_id)->get();
            return view('personalizadas.vistaEstimacionCompra', ['dias' => $dias, 'menus' => $menus]);
        }
    }

    public function reporte(Request $request)
    {
        if (backpack_user()->hasPermissionTo('calcularEstimacionCompra')) {

            $filtro_dia = $request->filtro_dias;

            $filtro_cantidad_semanas = $request->filtro_cantidad_semanas;

            $filtro_menus = collect($request->filtro_menu);

            $filtro_menus_cantidad = collect($request->filtro_menu_cantidad);

            $flagCC = false;
            foreach ($filtro_menus_cantidad as $mc) {
                if ($mc != null) {
                    $flagCC = true;
                }
            }
            //SI NO SE CARGO NADA, SE PIDE QUE CARGUE UNO DE LOS DOS
            if ($flagCC == false and $filtro_dia == null and $filtro_cantidad_semanas == null) {
                Alert::info('Debe completar "Estimacion Mediante Estadistica" o "Estimacion Mediante Cantidad de Comensales"')->flash();
                return Redirect::to('admin/calculoEstimacionCompra');
            }
            //SI SE CARGARON UNA COMBINACION DE AMBOS, SE PIDE QUE CARGUE SOLO UNO DE LOS DOS
            if (($filtro_dia != null and $filtro_cantidad_semanas != null and $flagCC == true)
                or ($filtro_dia == null and $filtro_cantidad_semanas != null and $flagCC == true)
                or ($filtro_dia != null and $filtro_cantidad_semanas == null and $flagCC == true)
            ) {
                Alert::info('Debe completar "Estimacion Mediante Estadistica" o "Estimacion Mediante Cantidad de Comensales", no ambos.')->flash();
                return Redirect::to('admin/calculoEstimacionCompra');
            }
            //VERIFICO QUE SE CARGUE LOS DOS DE ESTADISTICO JUNTOS
            if (($filtro_dia == null and $filtro_cantidad_semanas != null and $flagCC == false)
                or ($filtro_dia != null and $filtro_cantidad_semanas == null and $flagCC == false)
            ) {
                Alert::info('Debe seleccionar un "Dia" y completar "Semanas anteriores p/estadistica"')->flash();
                return Redirect::to('admin/calculoEstimacionCompra');
            }

            $manual = collect();
            $promedio = collect();

            if ($filtro_dia != null and $filtro_cantidad_semanas != null and $flagCC == false) {
                //CALCULO LAS CANTIDADES SEGUN LA ESTADISTICA DE LAS SEMANAS SOLICITADAS POR EL USUARIO
                switch ($filtro_dia) {
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
                for ($i = 1; $i <= $filtro_cantidad_semanas; $i++) {
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
            }
            if ($filtro_dia == null and $filtro_cantidad_semanas == null and $flagCC == true) {
                //ACA EL USUARIO YA ME DICE LAS CANTIDADES ENTONCES INSERTO ESAS CANTIDADES
                //EN UNA COLECCION QUE DESPUES SE USARA PARA EL CALCULO DE LOS INSUMOS A COMPRAR
                foreach ($filtro_menus_cantidad as $key => $value) {
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

            $menus = collect();

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

                        $aux = collect();
                        $aux->put('menu', Menu::find($menu_id)->descripcion);
                        $aux->put('plato', Plato::find($plato->id)->descripcion);
                        $aux->put('insumo', Insumo::find($insumo_plato->insumo->id)->descripcion);
                        $aux->put('estado', $flag);
                        if ($flag == false) {
                            $aux->put('cantidad', $cf_insumo . ' ' . Insumo::find($insumo_plato->insumo->id)->unidad_medida);
                        } else {
                            $aux->put('cantidad', $cs_insumo . ' ' . Insumo::find($insumo_plato->insumo->id)->unidad_medida);
                        }
                        $menus->push($aux);
                    }
                }
            }

            $menus = $menus->groupBy([
                'menu',
                function ($item) {
                    return $item['plato'];
                },    function ($item) {
                    return $item['insumo'];
                },
            ], $preserveKeys = true)->toArray();


            //JUNTO EL NOMBRE DEL MENU CON LA CANTIDAD (ESTIMACION MEDIANTE CANTIDAD DE COMENSALES), PARA PASAR AL REPORTE
            $cantidades_comensales_manual = collect();
            $filtro_menus->map(function ($item, $key1) use ($filtro_menus_cantidad, $cantidades_comensales_manual) {
                foreach ($filtro_menus_cantidad as $key2 => $value) {
                    if ($value != null) {
                        if ($key1 == $key2) {
                            $aux = collect();
                            $aux->put('menu', $item);
                            $aux->put('cantidad', $value);
                            $cantidades_comensales_manual->push($aux)->toArray();
                        }
                    }
                }
            });

            $cantidades_comensales_estadistica = collect();
            foreach ($promedio as $key => $value) {
                $aux = collect();
                $aux->put('menu', Menu::find($value['menu_id'])->descripcion);
                $aux->put('cantidad', $value['cantidad']);
                $cantidades_comensales_estadistica->push($aux)->toArray();
            }

            //FUNCION DE IMPRESION DEL PDF
            $html = view('reportes.reporteEstimacionCompra', [
                'menus' => $menus,'flagCC' => $flagCC,
                'filtro_dias' => $filtro_dia, 'filtro_cantidad_semanas' => $filtro_cantidad_semanas, 'cantidades_comensales_estadistica' => $cantidades_comensales_estadistica->toArray(), 
                'cantidades_comensales_manual' => $cantidades_comensales_manual->toArray(), 
            ]);

            $mpdf = new Mpdf([
                'margin_left' => '10',
                'margin_right' => '10',
                'margin_top' => '10',
                'margin_bottom' => '15',
                ]);
            $mpdf->setFooter('{PAGENO} / {nb}');
            $nombre = 'Reporte-Estimacion-Compra-' . Carbon::now()->format('d/m/Y G:i') . '.pdf';
            $mpdf->WriteHTML($html);
            $mpdf->Output($nombre, "I");
        }
    }
}
