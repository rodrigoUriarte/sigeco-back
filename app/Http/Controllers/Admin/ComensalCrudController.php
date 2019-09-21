<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ComensalRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ComensalCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ComensalCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Comensal');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/comensal');
        $this->crud->setEntityNameStrings('comensal', 'comensales');
        //$this->crud->setColumns(['user','comedor']);
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns(['user', 'comedor']);

        $this->crud->setColumnDetails('user', [
            'label' => 'Usuario',
            'type' => 'select',
            'name' => 'user_id', // the db column for the foreign key
            'entity' => 'user', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\BackpackUser" // foreign key model
        ]);

        $this->crud->setColumnDetails('comedor', [
            'label' => 'Comedor',
            'type' => 'select',
            'name' => 'comedor_id', // the db column for the foreign key
            'entity' => 'comedor', // the method that defines the relationship in your Model
            'attribute' => 'descripcion', // foreign key attribute that is shown to user
            'model' => "App\Models\Comedor" // foreign key model
        ]);
    }

    protected function setupShowOperation()
    {
        $this->crud->set('show.setFromDb', false);
        $this->setupListOperation();

    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(ComensalRequest::class);

        $this->crud->addField([  // Select2
            'label' => "Usuario",
            'type' => 'select2',
            'name' => 'user_id', // the db column for the foreign key
            'entity' => 'user', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\BackpackUser", // foreign key model

            // optional
            'default' => 1, // set the default value of the select2
            // 'options'   => (function ($query) {
            // return $query->orderBy('name', 'ASC')->where('depth', 1)->get();
            // }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
        ]);

        $this->crud->addField([  // Select2
            'label' => "Comedor",
            'type' => 'select2',
            'name' => 'comedor_id', // the db column for the foreign key
            'entity' => 'comedor', // the method that defines the relationship in your Model
            'attribute' => 'descripcion', // foreign key attribute that is shown to user
            'model' => "App\Models\Comedor", // foreign key model

            // optional
            'default' => 1, // set the default value of the select2

            //'options'   => (function ($query) {
            //return $query->orderBy('name', 'ASC')->where('depth', 1)->get();
            //}), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
