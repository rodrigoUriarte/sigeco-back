<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ComedorRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ComedorCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ComedorCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Comedor');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/comedor');
        $this->crud->setEntityNameStrings('comedor', 'comedores');

        if (backpack_user()->hasRole('admin')) {
            $this->crud->denyAccess(['create', 'update', 'delete', 'list', 'show']);
        }

        if (backpack_user()->hasRole('comensal')) {
            $this->crud->denyAccess(['create', 'update','delete','list','show']);
        }
        
    }


    protected function setupListOperation()
    {
        $this->crud->addColumns(['descripcion', 'direccion', 'unidad_academica']);

        $this->crud->setColumnDetails('unidad_academica', [
            'label' => 'Unidad Academica',
            'type' => 'select',
            'name' => 'unidad_academica_id', // the db column for the foreign key
            'entity' => 'unidadAcademica', // the method that defines the relationship in your Model
            'attribute' => 'nombre', // foreign key attribute that is shown to user
            'model' => "App\Models\UnidadAcademica", // foreign key model
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('unidadAcademica', function ($q) use ($column, $searchTerm) {
                    $q->where('nombre', 'like', '%' . $searchTerm . '%');
                    //->orWhereDate('fecha_inicio', '=', date($searchTerm));
                });
            },
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(ComedorRequest::class);

        $this->crud->addField([
            'name' => 'descripcion',
            'type' => 'text',
            'label' => 'Descripcion Comedor'
        ]);

        $this->crud->addField([
            'name' => 'direccion',
            'type' => 'address',
            'label' => 'Direccion Comedor',
        ]);

        $this->crud->addField([  // Select2
            'label' => "Unidad Academica",
            'type' => 'select2',
            'name' => 'unidad_academica_id', // the db column for the foreign key
            'entity' => 'unidadAcademica', // the method that defines the relationship in your Model
            'attribute' => 'nombre', // foreign key attribute that is shown to user
            'model' => "App\Models\UnidadAcademica", // foreign key model
            // optional
            'default' => 1, // set the default value of the select2
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
