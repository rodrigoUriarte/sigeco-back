<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\InscripcionRequest;
use App\Models\BackpackUser;
use App\Models\Inscripcion;
use App\Models\Menu;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;


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

        $this->crud->denyAccess(['create', 'update','delete','list','show']);

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
            $this->crud->setListView('personalizadas.vistaInscripcion', $this->data);
            $this->crud->addClause('where', 'comedor_id', '=', backpack_user()->persona->comedor_id);
        }
    }

    protected function setupListOperation()
    {
        //Si el usuario tiene rol de admin mostrar a que usuario corresponde cada inscripcion
        if (backpack_user()->hasRole('operativo')) {

            $this->crud->addColumns(['usuario']);

            $this->crud->setColumnDetails('usuario', [
                'label' => 'Usuario',
                'type' => 'select',
                'name' => 'user_id', // the db column for the foreign key
                'entity' => 'user', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model' => "App\Models\BackpackUser", // foreign key model
                'searchLogic' => function ($query, $column, $searchTerm) {
                    $query->orWhereHas('user', function ($q) use ($column, $searchTerm) {
                        $q->where('name', 'like', '%' . $searchTerm . '%');
                        //->orWhereDate('fecha_inicio', '=', date($searchTerm));
                    });
                },
            ]);
        }

        $this->crud->addColumns(['fecha_inscripcion', 'banda_horaria', 'menu_asignado']);

        $this->crud->setColumnDetails('fecha_inscripcion', [
            'name' => "fecha_inscripcion", // The db column name
            'label' => "Fecha Inscripcion", // Table column heading
            'type' => "date",
            // 'format' => 'l j F Y', // use something else than the base.default_date_format config value
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
                    //->orWhereDate('fecha_inicio', '=', date($searchTerm));
                });
            },
        ]);

        $this->crud->setColumnDetails('menu_asignado', [
            //NO FUNCIONA LA BUSQUEDA POR EL ATRIBUTO DEL MENU ASIGNADO
            'label' => 'Menu Asignado',
            'type' => 'select',
            'name' => 'menu_asignado_id', // the db column for the foreign key
            'entity' => 'menuAsignado', // the method that defines the relationship in your Model
            'attribute' => 'descripcionMenu', // foreign key attribute that is shown to user
            'model' => "App\Models\MenuAsignado", // foreign key model
            // 'searchLogic' => function ($query, $column, $searchTerm) {
            //     $query->orWhereHas('menuAsignado', function ($q) use ($column, $searchTerm) {
            //         $q->where('descripcionMenu', 'like', '%' . $searchTerm . '%');
            //         //->orWhereDate('fecha_inicio', '=', date($searchTerm));
            //     });
            // },
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(InscripcionRequest::class);

        $this->crud->addField([
            'name'  => 'user_id',
            'type'  => 'hidden',
            'value' => backpack_user()->id,
        ]);

        $this->crud->addField([
            'name'  => 'comedor_id',
            'type'  => 'hidden',
            'value' => backpack_user()->persona->comedor_id,
        ]);

        $this->crud->addField(
            [  // Select2
                'label' => "Menu Asignado",
                'type' => 'select2',
                'name' => 'menu_asignado_id', // the db column for the foreign key
                'entity' => 'menuAsignado', // the method that defines the relationship in your Model
                'attribute' => 'rangoFechas', // foreign key attribute that is shown to user
                'model' => "App\Models\MenuAsignado", // foreign key model
                // optional
                'default' => 0, // set the default value of the select2
                'options'   => (function ($query) {
                    return $query->where('user_id', '=', backpack_user()->id)->get();
                }),
                // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
            ]
        );
        $this->crud->addField(
            [
                'label' => "Banda Horaria",
                'type' => 'select2',
                'name' => 'banda_horaria_id', // the db column for the foreign key
                'entity' => 'bandaHoraria', // the method that defines the relationship in your Model
                'attribute' => 'descripcion', // foreign key attribute that is shown to user
                'model' => "App\Models\BandaHoraria", // foreign key model
                // optional
                'default' => 0, // set the default value of the select2
                'options'   => (function ($query) {
                    return $query->where('comedor_id', '=', backpack_user()->persona->comedor_id)->get();
                }),
            ]
        );

        $this->crud->addField([   // date_picker
            'name' => 'fecha_inscripcion',
            'type' => 'date_picker',
            'label' => 'Fecha Inscripcion',
            // optional:
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format' => 'dd-mm-yyyy',
                'language' => 'es',
                'startDate' => Carbon::now(),
                'defaultViewDate' => Carbon::now(),
                'daysOfWeekDisabled' => '0,6',
            ],
            'default' => Carbon::now()->toDateString(),
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function setupShowOperation()
    {
        $this->crud->set('show.setFromDb', false);
        $this->setupListOperation();
    }

    public function reporteInscripciones(Request $request)
    {

        $inscripciones = Inscripcion::all();

        $filtro_usuario = $request->filtro_usuario;
        $filtro_fecha_inscripcion = $request->filtro_fecha_inscripcion;
        $filtro_menu = $request->filtro_menu;

        $usuario = null;
        $fecha_inscripcion = null;
        $menu = null;

        if ($request->filtro_usuario != null) { //aca pregunto si el filtro que viene en el request esta vacio y sino hago el filtro y asi por cada if
            $usuario = BackpackUser::where('name', $filtro_usuario);
            foreach ($inscripciones as $id => $inscripcion) {
                if ($inscripcion->user->name != $filtro_usuario) {
                    $inscripciones->pull($id);
                }
            }
        }

        if ($request->filtro_fecha_inscripcion != null) { //aca pregunto si el filtro que viene en el request esta vacio y sino hago el filtro y asi por cada if
            $inscripcionx = Inscripcion::where('fecha_inscripcion', $filtro_fecha_inscripcion);
            foreach ($inscripciones as $id => $inscripcion) {
                if ($inscripcion->fecha_inscripcion != $filtro_fecha_inscripcion) {
                    $inscripciones->pull($id);
                }
            }
        }

        if ($request->filtro_menu != null) { //aca pregunto si el filtro que viene en el request esta vacio y sino hago el filtro y asi por cada if
            $menu = Menu::where('descripcion', $filtro_menu);
            foreach ($inscripciones as $id => $inscripcion) {
                if ($inscripcion->menuAsignado->menu->descripcion != $filtro_menu) {
                    $inscripciones->pull($id);
                }
            }
        }

        $pdf = PDF::loadView(
            'reportes.reporteInscripciones',
            compact('inscripciones', 'usuario', 'filtro_usuario', 'fecha_inscripcion', 'filtro_fecha_inscripcion', 'menu', 'filtro_menu')
        );

        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $y = $canvas->get_height() - 20;
        $pdf->getDomPDF()->get_canvas()->page_text(500, $y, "Pagina {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));

        $nombre = 'Reporte-Inscripciones-' . Carbon::now()->format('d/m/Y G:i') . '.pdf';
        return $pdf->stream($nombre);
    }
}
