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

        $this->crud->denyAccess(['create', 'update','delete','list','show']);

        if (backpack_user()->hasPermissionTo('createComedor')) {
            $this->crud->allowAccess('create');
        }
        if (backpack_user()->hasPermissionTo('updateComedor')) {
            $this->crud->allowAccess('update');
        }
        if (backpack_user()->hasPermissionTo('deleteComedor')) {
            $this->crud->allowAccess('delete');
        }
        if (backpack_user()->hasPermissionTo('listComedor')) {
            $this->crud->allowAccess('list');
        }
        if (backpack_user()->hasPermissionTo('showComedor')) {
            $this->crud->allowAccess('show');
        }
    }

    protected function setupListOperation()
    {
        $this->crud->hasAccessOrFail('list');
        $this->crud->addColumns(['descripcion', 'direccion', 'unidad_academica']);

        $this->crud->setColumnDetails('descripcion', [
            'name' => "descripcion", // The db column name
            'label' => "Descripcion", // Table column heading
            'type' => "text",
        ]);

        $this->crud->setColumnDetails('direccion', [
            'name' => "direccion", // The db column name
            'label' => "Direccion", // Table column heading
            'type' => "text",
        ]);

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
                });
            },
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->hasAccessOrFail('create');
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
        $this->crud->hasAccessOrFail('update');
        $this->setupCreateOperation();
    }

    protected function setupShowOperation()
    {
        $this->crud->hasAccessOrFail('show');
        $this->crud->set('show.setFromDb', false);
        $this->setupListOperation();
    }
}
