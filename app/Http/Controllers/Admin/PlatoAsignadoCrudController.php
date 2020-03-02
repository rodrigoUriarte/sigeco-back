<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PlatoAsignadoRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Models\Plato;
use App\Http\Controllers\Controller;


/**
 * Class PlatoAsignadoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PlatoAsignadoCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\PlatoAsignado');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/platoAsignado');
        $this->crud->setEntityNameStrings('Plato Asignado', 'Platos Asignados');

        $this->crud->denyAccess(['create', 'update', 'delete', 'list', 'show']);

        if (backpack_user()->hasPermissionTo('createPlatoAsignado')) {
            $this->crud->allowAccess('create');
        }
        if (backpack_user()->hasPermissionTo('updatePlatoAsignado')) {
            $this->crud->allowAccess('update');
        }
        if (backpack_user()->hasPermissionTo('deletePlatoAsignado')) {
            $this->crud->allowAccess('delete');
        }
        if (backpack_user()->hasPermissionTo('listPlatoAsignado')) {
            $this->crud->allowAccess('list');
        }
        if (backpack_user()->hasPermissionTo('showPlatoAsignado')) {
            $this->crud->allowAccess('show');
        }

        //SI el usuario es un admin muestra solo los platos asignados del comedor del cual es responsable
        if (backpack_user()->hasRole('operativo')) {
            $this->crud->addClause('where', 'comedor_id', '=', backpack_user()->persona->comedor_id);
        }
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns(['menu_id', 'plato_id', 'fecha']);

        $this->crud->setColumnDetails('menu_id', [
            'label' => 'Menu',
            'type' => 'select',
            'name' => 'menu_id', // the db column for the foreign key
            'entity' => 'menu', // the method that defines the relationship in your Model
            'attribute' => 'descripcion', // foreign key attribute that is shown to user
            'model' => "App\Models\Menu", // foreign key model
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('menu', function ($q) use ($column, $searchTerm) {
                    $q->where('descripcion', 'like', '%' . $searchTerm . '%');
                });
            },
        ]);

        $this->crud->setColumnDetails('plato_id', [
            'label' => 'Plato',
            'type' => 'select',
            'name' => 'plato_id', // the db column for the foreign key
            'entity' => 'plato', // the method that defines the relationship in your Model
            'attribute' => 'descripcion', // foreign key attribute that is shown to user
            'model' => "App\Models\Plato", // foreign key model
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('plato', function ($q) use ($column, $searchTerm) {
                    $q->where('descripcion', 'like', '%' . $searchTerm . '%');
                });
            },
        ]);

        $this->crud->setColumnDetails('fecha', [
            'name' => "fecha", // The db column name
            'label' => "Fecha", // Table column heading
            'type' => "date",
            // 'format' => 'l j F Y', // use something else than the base.default_date_format config value
        ]);

        // daterange filter
        $this->crud->addFilter(
            [
                'type'  => 'es_date_range',
                'name'  => 'fecha',
                'label' => 'Fecha'
            ],
            false,
            function ($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('where', 'fecha', '>=', $dates->from);
                $this->crud->addClause('where', 'fecha', '<=', $dates->to . ' 23:59:59');
            }
        );
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(PlatoAsignadoRequest::class);

        $this->crud->addField([   // date_picker
            'name' => 'fecha',
            'type' => 'date_picker',
            'label' => 'Fecha',
            // optional:
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format' => 'dd-mm-yyyy',
                'language' => 'es',
                'startDate' => Carbon::now(),
                'defaultViewDate' => Carbon::now(),
            ],
            'default' => Carbon::now()->toDateString(),
        ]);
        $this->crud->addField(
            [  // Select2
                'label' => "Menu",
                'type' => 'select2',
                'name' => 'menu_id', // the db column for the foreign key
                'entity' => 'menu', // the method that defines the relationship in your Model
                'attribute' => 'descripcion', // foreign key attribute that is shown to user
                'model' => "App\Models\Menu", // foreign key model
                'default' => 0, // set the default value of the select2
                'options'   => (function ($query) {
                    return $query->where('comedor_id', backpack_user()->persona->comedor_id)->get();
                }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
            ]
        );
        $this->crud->addField(
            [  // Select2
                'label' => "Plato",
                'type' => "select2_from_ajax",
                'name' => 'plato_id', // the db column for the foreign key
                'entity' => 'plato', // the method that defines the relationship in your Model
                'attribute' => "descripcion", // foreign key attribute that is shown to user
                'model' => "App\Models\Plato", // foreign key model
                'data_source' => url("admin/calculoPreparacionPlatos"), // url to controller search function (with /{id} should return model)
                'placeholder' => 'Seleccione un plato', // placeholder for the select
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'dependencies' => ['fecha', 'menu_id'],
                //SE RESETEA SOLO SI EL MENU CAMBIA NO CUANDO CAMBIA LA FECHA, A CORREGIR
            ]
        );
        $this->crud->addField([
            'name'  => 'comedor_id',
            'type'  => 'hidden',
            'value' => backpack_user()->persona->comedor_id,
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
