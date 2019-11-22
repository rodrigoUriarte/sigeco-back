<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\InscripcionRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Carbon;
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

        $this->crud->enableExportButtons();
        
        //Si el usuario tiene rol de comensal solo mostrar sus entradas
        if (backpack_user()->hasRole('comensal')) {
            $this->crud->addClause('where', 'user_id', '=', backpack_user()->id);
        }
    }

    protected function setupListOperation()
    {
        //Si el usuario tiene rol de admin mostrar a que usuario corresponde cada inscripcion
        if (backpack_user()->hasRole('admin')) {

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

        $this->crud->addColumns(['fecha_inscripcion', 'banda_horaria', 'menu_asignado', 'fecha_asistencia']);

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

            'label' => 'Menu Asignado',
            'type' => 'select',
            'name' => 'menu_asignado_id', // the db column for the foreign key
            'entity' => 'menuAsignado.menu', // the method that defines the relationship in your Model
            'attribute' => 'descripcion', // foreign key attribute that is shown to user
            'model' => "App\Models\Menu", // foreign key model
            // 'searchLogic' => function ($query, $column, $searchTerm) {
            //     $query->orWhereHas('descripcion', function ($q) use ($column, $searchTerm) {
            //         $q->where('descripcion', 'like', '%' . $searchTerm . '%');
            //         //->orWhereDate('fecha_inicio', '=', date($searchTerm));
            //     });
            // },
        ]);

        // $this->crud->setColumnDetails('menu_asignado', [

        //     ESTA TOMANDO EL ID DE LA INSCRIPCION PARA AL HACER LA LLAMADA AL METODO MENU
        //     LO DEJE COMO LA COLUMNA DE ARRIBA PERO NO FUNCIONA LA BUSQUEDA POR ESA COLUMNA

        //     'label' => 'Menu Asignado',
        //     'type' => 'select',
        //     'name' => 'menu_asignado_id', // the db column for the foreign key
        //     'entity' => 'menuAsignadoMenu', // the method that defines the relationship in your Model
        //     'attribute' => 'descripcion', // foreign key attribute that is shown to user
        //     'model' => "App\Models\Menu", // foreign key model
        //     // 'searchLogic' => function ($query, $column, $searchTerm) {
        //     //     $query->orWhereHas('descripcion', function ($q) use ($column, $searchTerm) {
        //     //         $q->where('descripcion', 'like', '%' . $searchTerm . '%');
        //     //         //->orWhereDate('fecha_inicio', '=', date($searchTerm));
        //     //     });
        //     // },
        // ]);
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
                    return $query->where('user_id','=',backpack_user()->id)->get();
                }), 
                    // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
            ]
        );
        $this->crud->addField(
            [
            'label' => "Banda Horaria",
            'type' => 'select2',
            'name' => 'banda_horaria_id', // the db column for the foreign key
            'entity' => 'banda_horaria', // the method that defines the relationship in your Model
            'attribute' => 'descripcion', // foreign key attribute that is shown to user
            'model' => "App\Models\BandaHoraria", // foreign key model
            // optional
            'default' => 0, // set the default value of the select2
            'options'   => (function ($query) {
                return $query->where('comedor_id','=',backpack_user()->persona->comedor_id)->get();
            }), 
        ]);

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
}
