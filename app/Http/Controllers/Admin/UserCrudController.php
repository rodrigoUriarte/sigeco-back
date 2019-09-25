<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\PermissionManager\app\Http\Controllers\UserCrudController as BackpackUserCrudController;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class UserCrudController extends BackpackUserCrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;


    public function setup()
    {
        $this->crud->setModel(config('backpack.base.user_model_fqn'));
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/user');
        $this->crud->setEntityNameStrings('user', 'users');
    }

    public function setupListOperation()
    {
        $this->crud->addColumns(['persona']);        
        $this->crud->setColumnDetails('persona', [
            'label' => 'Persona',
            'type' => 'select',
            'name' => 'persona_id', // the db column for the foreign key
            'entity' => 'persona', // the method that defines the relationship in your Model
            'attribute' => 'dni', // foreign key attribute that is shown to user
            'model' => "App\Models\Persona" // foreign key model
        ]);

        parent::setupListOperation();
    }

    public function setupCreateOperation()
    {
        $this->crud->setValidation(UserRequest::class);

        $this->crud->addField([  // Select2
            'label' => "Persona",
            'type' => 'select2',
            'name' => 'persona_id', // the db column for the foreign key
            'entity' => 'persona', // the method that defines the relationship in your Model
            'attribute' => 'dni', // foreign key attribute that is shown to user
            'model' => "App\Models\Persona", // foreign key model

            // optional
            'default' => 1, // set the default value of the select2
        ]);

        parent::setupCreateOperation();


    }

    public function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
