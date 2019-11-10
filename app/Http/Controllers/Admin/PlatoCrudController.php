<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PlatoRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PlatoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PlatoCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Plato');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/plato');
        $this->crud->setEntityNameStrings('plato', 'platos');
        
        //SI el usuario es un admin muestra solo los platos del comedor del cual es responsable
        if (backpack_user()->hasRole('admin')) {
            $this->crud->addClause('where', 'comedor_id', '=', backpack_user()->persona->comedor_id);
        }
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns(['comedor','menu', 'descripcion']);

        $this->crud->setColumnDetails('comedor', [
            'label' => 'Comedor',
            'type' => 'select',
            'name' => 'comedor_id', // the db column for the foreign key
            'entity' => 'comedor', // the method that defines the relationship in your Model
            'attribute' => 'descripcion', // foreign key attribute that is shown to user
            'model' => "App\Models\Comedor", // foreign key model
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('comedor', function ($q) use ($column, $searchTerm) {
                    $q->where('descripcion', 'like', '%' . $searchTerm . '%');
                    //->orWhereDate('fecha_inicio', '=', date($searchTerm));
                });
            },
        ]);

        $this->crud->setColumnDetails('menu', [
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
        
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(PlatoRequest::class);

        $this->crud->addField([
            'name'  => 'comedor_id',
            'type'  => 'hidden',
            'value' => backpack_user()->persona->comedor_id,
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
            [
                'label' => "Descripcion",
                'type' => 'text',
                'name' => 'descripcion',

            ]
        );
        // $this->crud->addField(
        //     [    // Select2Multiple = n-n relationship (with pivot table)
        //         'label'     => "Insumos",
        //         'type'      => 'select2_multiple',
        //         'name'      => 'insumos', // the method that defines the relationship in your Model
        //         'entity'    => 'insumos', // the method that defines the relationship in your Model
        //         'attribute' => 'descripcion', // foreign key attribute that is shown to user
        //         'model'     => "App\Models\Insumo", // foreign key model
        //         'pivot'     => true, // on create&update, do you need to add/delete pivot table entries?
        //         // 'select_all' => true, // show Select All and Clear buttons?

        //         // optional
        //         // 'options'   => (function ($query) {
        //         //     return $query->orderBy('name', 'ASC')->where('depth', 1)->get();
        //         // }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
        //    ]);

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
