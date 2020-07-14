<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ReglaRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ReglaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ReglaCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Regla');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/regla');
        $this->crud->setEntityNameStrings('regla', 'reglas');

        $this->crud->denyAccess(['create', 'update','delete','list','show']);

        if (backpack_user()->hasPermissionTo('createRegla')) {
            $this->crud->allowAccess('create');
        }
        if (backpack_user()->hasPermissionTo('updateRegla')) {
            $this->crud->allowAccess('update');
        }
        if (backpack_user()->hasPermissionTo('deleteRegla')) {
            $this->crud->allowAccess('delete');
        }
        if (backpack_user()->hasPermissionTo('listRegla')) {
            $this->crud->allowAccess('list');
        }
        if (backpack_user()->hasPermissionTo('showRegla')) {
            $this->crud->allowAccess('show');
        }

        //SI el usuario es un admin muestra solo los platos del comedor del cual es responsable
        if (backpack_user()->hasRole('operativo')) {
            $this->crud->addClause('where', 'comedor_id', '=', backpack_user()->persona->comedor_id);
        }
    }

    protected function setupListOperation()
    {
        $this->crud->hasAccessOrFail('list');
        $this->crud->addColumns(['descripcion', 'cantidad_faltas', 'tiempo', 'dias_sancion']);

        $this->crud->setColumnDetails('descripcion', [
            'name' => "descripcion", // The db column name
            'label' => "Descripcion", // Table column heading
            'type' => "text",
        ]);

        $this->crud->setColumnDetails('cantidad_faltas', [
            'name' => "cantidad_faltas", // The db column name
            'label' => "Cantidad Faltas", // Table column heading
            'type' => "number",
        ]);

        $this->crud->setColumnDetails('tiempo', [
            'name' => "tiempo", // The db column name
            'label' => "Tiempo", // Table column heading
            'type' => "text",
        ]);

        $this->crud->setColumnDetails('dias_sancion', [
            'name' => "dias_sancion", // The db column name
            'label' => "Dias Sancion", // Table column heading
            'type' => "number",
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->hasAccessOrFail('create');
        $this->crud->setValidation(ReglaRequest::class);

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
            ]
        );
        $this->crud->addField(
            [
                'name' => 'cantidad_faltas',
                'label' => "Cantidad de faltas a las que debe llegar un comensal para aplicar la sancion",
                'type' => "number",
            ]
        );
        $this->crud->addField(
            [   // select2_from_array
                'name' => 'tiempo',
                'label' => "Rango de tiempo de las faltas",
                'type' => 'select2_from_array',
                'options' => ['semana' => "Semana",'mes' => "Mes"],
                'allows_null' => false,
                'default' => 'one',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ]
        );
        $this->crud->addField(
            [
                'name' => 'dias_sancion',
                'label' => "Dias de sancion que se le aplicaran al comensal si llega a la cantidad de faltas",
                'type' => "number",
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
