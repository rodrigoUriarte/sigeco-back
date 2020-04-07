<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ParametroRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ParametroCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ParametroCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Parametro');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/parametro');
        $this->crud->setEntityNameStrings('parametro', 'parametros');

        $this->crud->denyAccess(['create', 'update','delete','list','show']);

        if (backpack_user()->hasPermissionTo('createParametro')) {
            $this->crud->allowAccess('create');
        }
        if (backpack_user()->hasPermissionTo('updateParametro')) {
            $this->crud->allowAccess('update');
        }
        if (backpack_user()->hasPermissionTo('deleteParametro')) {
            $this->crud->allowAccess('delete');
        }
        if (backpack_user()->hasPermissionTo('listParametro')) {
            $this->crud->allowAccess('list');
        }
        if (backpack_user()->hasPermissionTo('showParametro')) {
            $this->crud->allowAccess('show');
        }

        //SI el usuario es un admin muestra solo los menus del comedor del cual es responsable
        if (backpack_user()->hasRole('operativo')) {
            $this->crud->addClause('where', 'comedor_id', '=', backpack_user()->persona->comedor_id);
        }
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns(['limite_inscripcion', 'limite_menu_asignado', 'retirar']);

        $this->crud->setColumnDetails('limite_inscripcion', [
            'name' => "limite_inscripcion", // The db column name
            'label' => "Hora inscripciones automaticas", // Table column heading
            'type' => "datetime",
            'format' => 'HH:mm', // use something else than the base.default_datetime_format config value
        ]);

        $this->crud->setColumnDetails('limite_menu_asignado', [
            'name' => "limite_menu_asignado", // The db column name
            'label' => "Dia menus asignados automaticos", // Table column heading
            'type' => "number",
        ]);

        $this->crud->setColumnDetails('retirar', [
            'name' => 'retirar',
            'label' => 'Permitir Retirar',
            'type' => 'boolean',
            // optionally override the Yes/No texts
            'options' => [0 => 'NO', 1 => 'SI']
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(ParametroRequest::class);

        $this->crud->addField([
            'name'  => 'comedor_id',
            'type'  => 'hidden',
            'value' => backpack_user()->persona->comedor_id,
        ]);

        $this->crud->addField([
            'name' => 'limite_inscripcion',
            'label' => 'Hora del dia a la que se agregan las inscripciones automaticamente',
            'type' => 'time',
        ]);

        $this->crud->addField([
            'name' => 'limite_menu_asignado',
            'type' => 'number',
            'label' => 'Dia del mes para agregar menus asignados automaticamente, hasta el dia anterior lo haran los comensales'
        ]);

        $this->crud->addField([
            'name' => 'retirar',
            'label' => 'Permitir Retirar',
            'type' => 'checkbox'
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
