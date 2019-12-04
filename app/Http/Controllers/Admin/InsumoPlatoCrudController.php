<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\InsumoPlatoRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class InsumoPlatoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class InsumoPlatoCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\InsumoPlato');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/insumoPlato');
        $this->crud->setEntityNameStrings('Insumo Plato', 'Insumos Platos');

         //SI el usuario es un admin muestra solo los insumosXplato del comedor del cual es responsable
         if (backpack_user()->hasRole('admin')) {
            $this->crud->addClause('where', 'comedor_id', '=', backpack_user()->persona->comedor_id);
        }

        if (backpack_user()->hasRole('comensal')) {
            $this->crud->denyAccess(['create', 'update','delete','list','show']);
        }
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns(['menu','plato', 'insumo', 'cantidad']);

        $this->crud->setColumnDetails('menu', [
            'label' => 'Menu',
            'type' => 'select',
            'name' => 'plato_id', // the db column for the foreign key
            'entity' => 'plato', // the method that defines the relationship in your Model
            'attribute' => 'nombreMenu', // foreign key attribute that is shown to user
            'model' => "App\Models\Plato", // foreign key model
            // 'searchLogic' => function ($query, $column, $searchTerm) {
            //     $query->orWhereHas('plato', function ($q) use ($column, $searchTerm) {
            //         $q->where('nombreMenu', 'like', '%' . $searchTerm . '%');
            //         //->orWhereDate('fecha_inicio', '=', date($searchTerm));
            //     });
            // },
        ]);

        $this->crud->setColumnDetails('plato', [
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

        $this->crud->setColumnDetails('insumo', [
            'label' => 'Insumo',
            'type' => 'select',
            'name' => 'insumo_id', // the db column for the foreign key
            'entity' => 'insumo', // the method that defines the relationship in your Model
            'attribute' => 'descripcion', // foreign key attribute that is shown to user
            'model' => "App\Models\Insumo", // foreign key model
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('insumo', function ($q) use ($column, $searchTerm) {
                    $q->where('descripcion', 'like', '%' . $searchTerm . '%');
                    //->orWhereDate('fecha_inicio', '=', date($searchTerm));
                });
            },
        ]);

    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(InsumoPlatoRequest::class);

        $this->crud->addField([
            'name'  => 'comedor_id',
            'type'  => 'hidden',
            'value' => backpack_user()->persona->comedor_id,
        ]);

        $this->crud->addField(
            [  // Select2
                'label' => "Plato",
                'type' => 'select2',
                'name' => 'plato_id', // the db column for the foreign key
                'entity' => 'plato', // the method that defines the relationship in your Model
                'attribute' => 'descripcion', // foreign key attribute that is shown to user
                'model' => "App\Models\Plato", // foreign key model
                'default' => 0, // set the default value of the select2
                'options'   => (function ($query) {
                    return $query->where('comedor_id', backpack_user()->persona->comedor_id)->get();
                }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
            ]
        );
        $this->crud->addField(
            [  // Select2
                'label' => "Insumo",
                'type' => 'select2',
                'name' => 'insumo_id', // the db column for the foreign key
                'entity' => 'insumo', // the method that defines the relationship in your Model
                'attribute' => 'descripcionUM', // foreign key attribute that is shown to user
                'model' => "App\Models\Insumo", // foreign key model
                'default' => 0, // set the default value of the select2
                'options'   => (function ($query) {
                    return $query->where('comedor_id', backpack_user()->persona->comedor_id)->get();
                }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
            ]
        );

        //deberia ser un select la cantidad, o sea tener una tabla de unidades de medida
        $this->crud->addField(
            [
                'name' => 'cantidad',
                'label' => "Cantidad necesaria por plato",
                'type' => "number",
            ]
        );
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
