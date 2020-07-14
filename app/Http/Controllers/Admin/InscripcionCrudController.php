<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\InscripcionRequest;
use App\User;
use App\Models\Inscripcion;
use App\Models\Menu;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Carbon\Carbon as CarbonCarbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException;
use Prologue\Alerts\Facades\Alert;
use Mpdf\Mpdf;

/**
 * Class InscripcionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class InscripcionCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Inscripcion');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/inscripcion');
        $this->crud->setEntityNameStrings('inscripcion', 'inscripciones');

        $this->crud->denyAccess(['create', 'update', 'delete', 'list', 'show']);

        if (backpack_user()->hasPermissionTo('createInscripcion')) {
            $this->crud->allowAccess('create');
        }
        if (backpack_user()->hasPermissionTo('updateInscripcion')) {
            $this->crud->allowAccess('update');
        }
        if (backpack_user()->hasPermissionTo('deleteInscripcion')) {
            $this->crud->allowAccess('delete');
        }
        if (backpack_user()->hasPermissionTo('listInscripcion')) {
            $this->crud->allowAccess('list');
        }
        if (backpack_user()->hasPermissionTo('showInscripcion')) {
            $this->crud->allowAccess('show');
        }

        //Si el usuario tiene rol de comensal solo mostrar sus entradas
        if (backpack_user()->hasRole('comensal')) {
            $this->crud->addClause('where', 'user_id', '=', backpack_user()->id);
        }

        //SI el usuario es un admin muestra solo los insumos del comedor del cual es responsable
        if (backpack_user()->hasRole('operativo')) {
            //PASO LOS DATOS PARA EL REPORTE
            $menus = Menu::where('comedor_id', backpack_user()->persona->comedor_id)->get();
            $this->data['menus'] = $menus;
            $this->crud->setListView('personalizadas.vistaInscripcion', $this->data);
            $this->crud->addClause('whereHas', 'user.persona', function ($query) {
                $query->where('comedor_id', backpack_user()->persona->comedor_id);
            });        
        }
    }

    protected function setupListOperation()
    {
        $this->crud->hasAccessOrFail('list');
        // //Si el usuario tiene rol de admin mostrar a que usuario corresponde cada inscripcion
        if (backpack_user()->hasRole('operativo')) {

            $this->crud->addColumns(['usuario']);

            $this->crud->setColumnDetails('usuario', [
                'label' => 'Usuario',
                'type' => 'select',
                'name' => 'user_id', // the db column for the foreign key
                'entity' => 'user', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model' => "App\User", // foreign key model
                'searchLogic' => function ($query, $column, $searchTerm) {
                    $query->orWhereHas('user', function ($q) use ($column, $searchTerm) {
                        $q->where('name', 'like', '%' . $searchTerm . '%');
                    });
                },
                'orderable' => true,
                'orderLogic' => function ($query, $column, $columnDirection) {
                    return $query->leftJoin('users', 'users.id', '=', 'inscripciones.user_id')
                        ->orderBy('users.name', $columnDirection)
                        ->select('inscripciones.*');
                }
            ]);
        }

        $this->crud->addColumns(['fecha_inscripcion', 'banda_horaria', 'menu_asignado', 'retira']);

        $this->crud->setColumnDetails('fecha_inscripcion', [
            'name' => "fecha_inscripcion", // The db column name
            'label' => "Fecha Inscripcion", // Table column heading
            'type' => "date",
        ]);

        $this->crud->setColumnDetails('banda_horaria', [
            'label' => 'Banda Horaria',
            'type' => 'select',
            'name' => 'banda_horaria_id', // the db column for the foreign key
            'entity' => 'bandaHoraria', // the method that defines the relationship in your Model
            'attribute' => 'descripcion', // foreign key attribute that is shown to user
            'model' => "App\Models\BandaHoraria", // foreign key model
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('bandaHoraria', function ($q) use ($column, $searchTerm) {
                    $q->where('descripcion', 'like', '%' . $searchTerm . '%');
                });
            },
            'orderable' => true,
            'orderLogic' => function ($query, $column, $columnDirection) {
                return $query->leftJoin('bandas_horarias', 'bandas_horarias.id', '=', 'inscripciones.banda_horaria_id')
                    ->orderBy('bandas_horarias.hora_inicio', $columnDirection)
                    ->select('inscripciones.*');
            }
        ]);

        $this->crud->setColumnDetails('menu_asignado', [
            //NO FUNCIONA LA BUSQUEDA POR EL ATRIBUTO DEL MENU ASIGNADO
            'label' => 'Menu Asignado',
            'type' => 'select',
            'name' => 'menu_asignado_id', // the db column for the foreign key
            'entity' => 'menuAsignado', // the method that defines the relationship in your Model
            'attribute' => "descripcion_menu", // foreign key attribute that is shown to user
            'model' => "App\Models\MenuAsignado", // foreign key model
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('menuAsignado.menu', function ($q) use ($column, $searchTerm) {
                    $q->where('descripcion', 'like', '%' . $searchTerm . '%');
                });
            },
            'orderable' => true,
            'orderLogic' => function ($query, $column, $columnDirection) {
                return $query->leftJoin('menus_asignados', 'menus_asignados.id', '=', 'inscripciones.menu_asignado_id')
                    ->leftJoin('menus', 'menus.id', '=', 'menus_asignados.menu_id')
                    ->orderBy('menus.descripcion', $columnDirection)
                    ->select('inscripciones.*');
            }
        ]);

        $this->crud->setColumnDetails('retira', [
            'name' => 'retira',
            'label' => 'Retira',
            'type' => 'boolean',
            // optionally override the Yes/No texts
            'options' => [0 => 'NO', 1 => 'SI']
        ]);

        //daterange filter
        $this->crud->addFilter(
            [
                'type'  => 'date_range',
                'name'  => 'fecha_inscripcion',
                'label' => 'Fecha Inscripcion'
            ],
            false,
            function ($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('where', 'fecha_inscripcion', '>=', $dates->from);
                $this->crud->addClause('where', 'fecha_inscripcion', '<=', $dates->to . ' 23:59:59');
            }
        );
    }

    protected function setupCreateOperation()
    {
        $this->crud->hasAccessOrFail('create');
    
    }

    protected function setupUpdateOperation()
    {
        $this->crud->hasAccessOrFail('update');
        // $this->setupCreateOperation();
    }

    protected function setupShowOperation()
    {
        $this->crud->hasAccessOrFail('show');
        $this->crud->set('show.setFromDb', false);
        $this->setupListOperation();
    }

    public function reporteInscripciones(Request $request)
    {

        $inscripciones = Inscripcion::all();

        $filtro_comensal = $request->filtro_comensal;
        $filtro_fecha_inscripcion_desde = $request->filtro_fecha_inscripcion_desde;
        $filtro_fecha_inscripcion_hasta = $request->filtro_fecha_inscripcion_hasta;
        $filtro_menu = $request->filtro_menu;

        if (($filtro_fecha_inscripcion_desde > $filtro_fecha_inscripcion_hasta) and
            ($filtro_fecha_inscripcion_desde != null and $filtro_fecha_inscripcion_hasta != null)
        ) {
            Alert::info('El dato "fecha desde" no puede ser mayor a "fecha hasta"')->flash();
            return Redirect::to('admin/inscripcion');
        }

        if ($filtro_comensal != null) { //aca pregunto si el filtro que viene en el request esta vacio y sino hago el filtro y asi por cada if
            foreach ($inscripciones as $id => $inscripcion) {
                if ($inscripcion->user->id != $filtro_comensal) {
                    $inscripciones->pull($id);
                }
            }
        }

        if ($filtro_fecha_inscripcion_desde != null) { //aca pregunto si el filtro que viene en el request esta vacio y sino hago el filtro y asi por cada if
            foreach ($inscripciones as $id => $inscripcion) {
                if ($inscripcion->fecha_inscripcion < $filtro_fecha_inscripcion_desde) {
                    $inscripciones->pull($id);
                }
            }
            //DESPUES DE USAR EL FILTRO PARA LAS OPERACIONES, PASO EL FILTRO A LA VISTA CON EL FORMATO CORRECTO
            $myDate = Date::createFromFormat('Y-m-d', $filtro_fecha_inscripcion_desde);
            $filtro_fecha_inscripcion_desde = date_format($myDate, 'd-m-Y');
        }

        if ($filtro_fecha_inscripcion_hasta != null) { //aca pregunto si el filtro que viene en el request esta vacio y sino hago el filtro y asi por cada if
            foreach ($inscripciones as $id => $inscripcion) {
                if ($inscripcion->fecha_inscripcion > $filtro_fecha_inscripcion_hasta) {
                    $inscripciones->pull($id);
                }
            }
            //DESPUES DE USAR EL FILTRO PARA LAS OPERACIONES, PASO EL FILTRO A LA VISTA CON EL FORMATO CORRECTO
            $myDate2 = Date::createFromFormat('Y-m-d', $filtro_fecha_inscripcion_hasta);
            $filtro_fecha_inscripcion_hasta = date_format($myDate2, 'd-m-Y');
        }

        if ($filtro_menu != null) { //aca pregunto si el filtro que viene en el request esta vacio y sino hago el filtro y asi por cada if
            foreach ($inscripciones as $id => $inscripcion) {
                if ($inscripcion->menuAsignado->menu->descripcion != $filtro_menu) {
                    $inscripciones->pull($id);
                }
            }
        }

        $html = view(
            'reportes.reporteInscripciones',
            [
                'inscripciones' => $inscripciones
                ->sortBy(function ($inscripcion, $key) {
                    return [
                        $inscripcion->fecha_inscripcion,
                        $inscripcion->user->name,
                    ];
                })
                ->groupBy(['menuAsignado.menu_id'], $preserveKeys = true), 
                'filtro_comensal' => $filtro_comensal,
                'filtro_fecha_inscripcion_desde' => $filtro_fecha_inscripcion_desde,
                'filtro_fecha_inscripcion_hasta' => $filtro_fecha_inscripcion_hasta,
                'filtro_menu' => $filtro_menu
            ]
        );

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
