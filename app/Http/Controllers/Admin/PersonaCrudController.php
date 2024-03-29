<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PersonaRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Database\Eloquent\Builder;

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

        $this->crud->denyAccess(['create', 'update', 'delete', 'list', 'show']);

        if (backpack_user()->hasPermissionTo('createPersona')) {
            $this->crud->allowAccess('create');
        }
        if (backpack_user()->hasPermissionTo('updatePersona')) {
            $this->crud->allowAccess('update');
        }
        if (backpack_user()->hasPermissionTo('deletePersona')) {
            $this->crud->allowAccess('delete');
        }
        if (backpack_user()->hasPermissionTo('listPersona')) {
            $this->crud->allowAccess('list');
        }
        if (backpack_user()->hasPermissionTo('showPersona')) {
            $this->crud->allowAccess('show');
        }

        //SI el usuario es un admin muestra solo las personas del comedor del cual es responsable
        if (backpack_user()->hasRole('operativo')) {
            $this->crud->addClause('where', 'comedor_id', '=', backpack_user()->persona->comedor_id);
        }
    }

    protected function setupListOperation()
    {
        $this->crud->hasAccessOrFail('list');
        $this->crud->addColumns(['dni', 'nombre', 'apellido', 'telefono', 'email', 'unidad_academica', 'comedor']);

        $this->crud->setColumnDetails('dni', [
            'name' => "dni", // The db column name
            'label' => "DNI", // Table column heading
            'type' => "text",
        ]);

        $this->crud->setColumnDetails('nombre', [
            'name' => "nombre", // The db column name
            'label' => "Nombre", // Table column heading
            'type' => "text",
        ]);

        $this->crud->setColumnDetails('apellido', [
            'name' => "apellido", // The db column name
            'label' => "Apellido", // Table column heading
            'type' => "text",
        ]);

        $this->crud->setColumnDetails('telefono', [
            'name' => 'telefono', // The db column name
            'label' => "Telefono", // Table column heading
            'type' => 'phone',
            // 'limit' => 10, // if you want to truncate the phone number to a different number of characters
        ]);

        $this->crud->setColumnDetails('email', [
            'name' => "email", // The db column name
            'label' => "Email", // Table column heading
            'type' => "email",
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
                });
            },
        ]);

        $this->crud->setColumnDetails('usuario', [
            'label' => 'Usuario',
            'type' => 'select',
            'name' => 'user_id', // the db column for the foreign key
            'entity' => 'user', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\User", // foreign key model
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('user', function ($q) use ($column, $searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%');
                });
            },
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->hasAccessOrFail('create');
        $this->crud->setValidation(PersonaRequest::class);

        $this->crud->addField([   // Number
            'name' => 'dni',
            'label' => 'DNI',
            'type' => 'number',
            // optionals
            // 'attributes' => ["step" => "any"], // allow decimals
            // 'prefix' => "$",
            // 'suffix' => ".00",
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
        $this->crud->addField([   // Number
            'name' => 'telefono',
            'label' => 'Telefono',
            'type' => 'text',
            // optionals
            // 'attributes' => ["step" => "any"], // allow decimals
            // 'prefix' => "$",
            // 'suffix' => ".00",
        ]);
        $this->crud->addField([
            'name' => 'email',
            'type' => 'email',
            'label' => 'Email'
        ]);

         if (backpack_user()->hasRole('superAdmin')) {

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
        }

        if (backpack_user()->hasRole('operativo')) {
            $this->crud->addField([  // Select2
                'label' => "Unidad Academica",
                'type' => 'select2',
                'name' => 'unidad_academica_id', // the db column for the foreign key
                'entity' => 'unidadAcademica', // the method that defines the relationship in your Model
                'attribute' => 'nombre', // foreign key attribute that is shown to user
                'model' => "App\Models\UnidadAcademica", // foreign key model

                // optional
                'default' => 1, // set the default value of the select2
                'options'   => (function ($query) {
                    return $query->where('id', backpack_user()->persona->unidad_academica_id)
                        ->get();
                }),
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
                'options'   => (function ($query) {
                        return $query->where('id', backpack_user()->persona->comedor_id)
                            ->get();
                }),
            ]);
        }
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
