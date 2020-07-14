<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DiaServicioRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class DiaServicioCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DiaServicioCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\DiaServicio');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/diaservicio');
        $this->crud->setEntityNameStrings('Dia Servicio', 'Dias Servicio');

        $this->crud->denyAccess(['create', 'update', 'delete', 'list', 'show']);

        if (backpack_user()->hasPermissionTo('createDiaServicio')) {
            $this->crud->allowAccess('create');
        }
        if (backpack_user()->hasPermissionTo('updateDiaServicio')) {
            $this->crud->allowAccess('update');
        }
        if (backpack_user()->hasPermissionTo('deleteDiaServicio')) {
            $this->crud->allowAccess('delete');
        }
        if (backpack_user()->hasPermissionTo('listDiaServicio')) {
            $this->crud->allowAccess('list');
        }
        if (backpack_user()->hasPermissionTo('showDiaServicio')) {
            $this->crud->allowAccess('show');
        }

        //SI el usuario es un admin muestra solo los dias del comedor del cual es responsable
        if (backpack_user()->hasRole('operativo')) {
            $this->crud->addClause('where', 'comedor_id', '=', backpack_user()->persona->comedor_id);
        }
    }

    protected function setupListOperation()
    {
        $this->crud->hasAccessOrFail('list');

        $this->crud->addColumns(['dia']);

        $this->crud->setColumnDetails('dia', [
            'name' => "dia", // The db column name
            'label' => "Dia de Servicio", // Table column heading
            'type' => "text",
        ]);

    }

    protected function setupCreateOperation()
    {
        $this->crud->hasAccessOrFail('create');
        $this->crud->setValidation(DiaServicioRequest::class);

        $this->crud->addField([
            'name'  => 'comedor_id',
            'type'  => 'hidden',
            'value' => backpack_user()->persona->comedor_id,
        ]);

        $this->crud->addField([
            'name' => 'dia',
            'label' => 'Dia de Servicio',
            'type' => 'enum'
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
