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
                    //->orWhereDate('fecha_inicio', '=', date($searchTerm));
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
                    //->orWhereDate('fecha_inicio', '=', date($searchTerm));
                });
            },
        ]);

    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(PlatoAsignadoRequest::class);

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
                'data_source' => url("api/plato"), // url to controller search function (with /{id} should return model)
                'placeholder' => 'Seleccione un plato', // placeholder for the select
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'dependencies' => ['menu_id'],
            ]
        );
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
