<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MenuRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MenuCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class MenuCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Menu');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/menu');
        $this->crud->setEntityNameStrings('menu', 'menus');

        $this->crud->denyAccess(['create', 'update','delete','list','show']);

        if (backpack_user()->hasPermissionTo('createMenu')) {
            $this->crud->allowAccess('create');
        }
        if (backpack_user()->hasPermissionTo('updateMenu')) {
            $this->crud->allowAccess('update');
        }
        if (backpack_user()->hasPermissionTo('deleteMenu')) {
            $this->crud->allowAccess('delete');
        }
        if (backpack_user()->hasPermissionTo('listMenu')) {
            $this->crud->allowAccess('list');
        }
        if (backpack_user()->hasPermissionTo('showMenu')) {
            $this->crud->allowAccess('show');
        }

        //SI el usuario es un admin muestra solo los menus del comedor del cual es responsable
        if (backpack_user()->hasRole('operativo')) {
            $this->crud->addClause('where', 'comedor_id', '=', backpack_user()->persona->comedor_id);
        }

    }

    protected function setupListOperation()
    {

        $this->crud->addColumns(['descripcion']);

        $this->crud->setColumnDetails('descripcion', [
            'name' => "descripcion", // The db column name
            'label' => "Descripcion", // Table column heading
            'type' => "text",
        ]);

    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(MenuRequest::class);

        $this->crud->addField([
            'name'  => 'comedor_id',
            'type'  => 'hidden',
            'value' => backpack_user()->persona->comedor_id,
        ]);

        $this->crud->addField([
            'name' => 'descripcion',
            'type' => 'text',
            'label' => 'Descripcion'
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
