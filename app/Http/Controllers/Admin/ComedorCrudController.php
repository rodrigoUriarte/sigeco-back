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
    }

    protected function setupListOperation()
    {
        $this->crud->setColumns(['descripcion','direccion']);
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
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
