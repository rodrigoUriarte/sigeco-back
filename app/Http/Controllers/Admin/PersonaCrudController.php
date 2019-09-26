<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PersonaRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PersonaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PersonaCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Persona');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/persona');
        $this->crud->setEntityNameStrings('persona', 'personas');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns(['dni', 'nombre', 'apellido', 'telefono', 'unidad_academica', 'comedor', 'usuario']);

        $this->crud->setColumnDetails('unidad_academica', [
            'label' => 'Unidad Academica',
            'type' => 'select',
            'name' => 'unidad_academica_id', // the db column for the foreign key
            'entity' => 'unidad_academica', // the method that defines the relationship in your Model
            'attribute' => 'nombre', // foreign key attribute that is shown to user
            'model' => "App\Models\UnidadAcademica" // foreign key model
        ]);

        $this->crud->setColumnDetails('comedor', [
            'label' => 'Comedor',
            'type' => 'select',
            'name' => 'comedor_id', // the db column for the foreign key
            'entity' => 'comedor', // the method that defines the relationship in your Model
            'attribute' => 'descripcion', // foreign key attribute that is shown to user
            'model' => "App\Models\Comedor" // foreign key model
        ]);

        $this->crud->setColumnDetails('usuario', [
            'label' => 'Usuario',
            'type' => 'select',
            'name' => 'user_id', // the db column for the foreign key
            'entity' => 'user', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\BackpackUser" // foreign key model
        ]);
        
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(PersonaRequest::class);

        $this->crud->addField([
            'name' => 'dni',
            'type' => 'text',
            'label' => 'DNI'
        ]);
        $this->crud->addField([
            'name' => 'nombre',
            'type' => 'text',
            'label' => 'Nombre'
        ]);
        $this->crud->addField([
            'name' => 'apellido',
            'type' => 'text',
            'label' => 'Apellido'
        ]);
        $this->crud->addField([
            'name' => 'telefono',
            'type' => 'text',
            'label' => 'Telelfono'
        ]);
        $this->crud->addField([  // Select2
            'label' => "Unidad Academica",
            'type' => 'select2',
            'name' => 'unidad_academica_id', // the db column for the foreign key
            'entity' => 'unidad_academica', // the method that defines the relationship in your Model
            'attribute' => 'nombre', // foreign key attribute that is shown to user
            'model' => "App\Models\UnidadAcademica", // foreign key model

            // optional
            'default' => 1, // set the default value of the select2
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
        ]);
        $this->crud->addField([  // Select2
            'label' => "Usuario",
            'type' => 'select2',
            'name' => 'user_id', // the db column for the foreign key
            'entity' => 'user', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\BackpackUser", // foreign key model

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
