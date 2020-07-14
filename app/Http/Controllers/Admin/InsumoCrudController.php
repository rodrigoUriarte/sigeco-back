<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\InsumoRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class InsumoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class InsumoCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Insumo');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/insumo');
        $this->crud->setEntityNameStrings('insumo', 'insumos');
        
        $this->crud->denyAccess(['create', 'update','delete','list','show']);

        if (backpack_user()->hasPermissionTo('createInsumo')) {
            $this->crud->allowAccess('create');
        }
        if (backpack_user()->hasPermissionTo('updateInsumo')) {
            $this->crud->allowAccess('update');
        }
        if (backpack_user()->hasPermissionTo('deleteInsumo')) {
            $this->crud->allowAccess('delete');
        }
        if (backpack_user()->hasPermissionTo('listInsumo')) {
            $this->crud->allowAccess('list');
        }
        if (backpack_user()->hasPermissionTo('showInsumo')) {
            $this->crud->allowAccess('show');
        }

        //SI el usuario es un admin muestra solo los insumos del comedor del cual es responsable
        if (backpack_user()->hasRole('operativo')) {
            $this->crud->addClause('where', 'comedor_id', '=', backpack_user()->persona->comedor_id);
        }

    }

    protected function setupListOperation()
    {
        $this->crud->hasAccessOrFail('list');
        $this->crud->addColumns(['descripcion','unidad_medida']);

        $this->crud->setColumnDetails('descripcion', [
            'name' => "descripcion", // The db column name
            'label' => "Descripcion", // Table column heading
            'type' => "text",
        ]);

        $this->crud->setColumnDetails('unidad_medida', [
            'name' => "unidad_medida", // The db column name
            'label' => "Unidad Medida", // Table column heading
            'type' => "text",
        ]);

    }

    protected function setupCreateOperation()
    {
        $this->crud->hasAccessOrFail('create');
        $this->crud->setValidation(InsumoRequest::class);

        $this->crud->addField([
            'name'  => 'comedor_id',
            'type'  => 'hidden',
            'value' => backpack_user()->persona->comedor_id,
        ]);
        
        $this->crud->addField(
            [
            'label' => "Descripcion",
            'type' => 'text',
            'name' => 'descripcion',

        ]);
        $this->crud->addField(
            [
            'label' => "Unidad Medida",
            'type' => 'text',
            'name' => 'unidad_medida',

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
