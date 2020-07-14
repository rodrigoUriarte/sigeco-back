<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProveedorRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ProveedorCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProveedorCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Proveedor');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/proveedor');
        $this->crud->setEntityNameStrings('proveedor', 'proveedores');

        $this->crud->denyAccess(['create', 'update', 'delete', 'list', 'show']);

        if (backpack_user()->hasPermissionTo('createProveedor')) {
            $this->crud->allowAccess('create');
        }
        if (backpack_user()->hasPermissionTo('updateProveedor')) {
            $this->crud->allowAccess('update');
        }
        if (backpack_user()->hasPermissionTo('deleteProveedor')) {
            $this->crud->allowAccess('delete');
        }
        if (backpack_user()->hasPermissionTo('listProveedor')) {
            $this->crud->allowAccess('list');
        }
        if (backpack_user()->hasPermissionTo('showProveedor')) {
            $this->crud->allowAccess('show');
        }

        //SI el usuario es un admin muestra solo los platos del comedor del cual es responsable
        if (backpack_user()->hasRole('operativo')) {
            $this->crud->addClause('whereHas', 'comedores', function ($query) {
                $query->where('comedor_id', backpack_user()->persona->comedor_id);
            })->get();
        }
    }

    protected function setupListOperation()
    {
        $this->crud->hasAccessOrFail('list');
        $this->crud->addColumns(['cuit','nombre', 'telefono', 'email', 'direccion']);

        $this->crud->setColumnDetails('cuit', [
            'name' => "cuit_masked", // The db column name
            'label' => "CUIT/CUIL", // Table column heading
            'type' => "text",
        ]);
        $this->crud->setColumnDetails('nombre', [
            'name' => "nombre", // The db column name
            'label' => "Nombre", // Table column heading
            'type' => "text",
        ]);

        $this->crud->setColumnDetails('telefono', [
            'name' => "telefono", // The db column name
            'label' => "Telefono", // Table column heading
            'type' => "text",
        ]);

        $this->crud->setColumnDetails('email', [
            'name' => "email", // The db column name
            'label' => "Email", // Table column heading
            'type' => "text",
        ]);

        $this->crud->setColumnDetails('direccion', [
            'name' => "direccion", // The db column name
            'label' => "Direccion", // Table column heading
            'type' => "address",
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->hasAccessOrFail('create');
        $this->crud->setValidation(ProveedorRequest::class);

        $this->crud->addField(
            [
                'label' => "CUIT/CUIL",
                'type' => 'cuit',
                'name' => 'cuit',
            ]
        );

        $this->crud->addField(
            [
                'label' => "Nombre",
                'type' => 'text',
                'name' => 'nombre',
            ]
        );

        $this->crud->addField(
            [
                'label' => "Telefono",
                'type' => 'text',
                'name' => 'telefono',
            ]
        );

        $this->crud->addField(
            [
                'label' => "Email",
                'type' => 'text',
                'name' => 'email',
            ]
        );

        $this->crud->addField(
            [
                'label' => "Direccion",
                'type' => 'address_algolia',
                'name' => 'direccion',
            ]
        );

        $this->crud->addField(
            [    // Select2Multiple = n-n relationship (with pivot table)
                'label'     => "Comedores",
                'type'      => 'select2_multiple',
                'name'      => 'comedores', // the method that defines the relationship in your Model
                'entity'    => 'comedores', // the method that defines the relationship in your Model
                'attribute' => 'descripcion', // foreign key attribute that is shown to user
           
                'pivot'     => true, // on create&update, do you need to add/delete pivot table entries?
                'select_all' => true, // show Select All and Clear buttons?
           
                // optional
                'model'     => "App\Models\Comedor", // foreign key model
                // 'options'   => (function ($query) {
                //     return $query->where('id', backpack_user()->persona->comedor_id)->get();
                // }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
           ]
        );
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
