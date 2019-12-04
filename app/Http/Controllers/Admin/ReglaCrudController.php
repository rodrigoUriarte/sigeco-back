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

        //SI el usuario es un admin muestra solo los platos del comedor del cual es responsable
        if (backpack_user()->hasRole('admin')) {
            $this->crud->addClause('where', 'comedor_id', '=', backpack_user()->persona->comedor_id);
        }

        if (backpack_user()->hasRole('comensal')) {
            $this->crud->denyAccess(['create', 'update', 'delete', 'list', 'show']);
        }
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns(['descripcion', 'cantidad_faltas', 'tiempo', 'dias_sancion']);
    }

    protected function setupCreateOperation()
    {
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
                'label' => "Cantidad Faltas",
                'type' => "number",
            ]
        );
        $this->crud->addField(
            [   // select2_from_array
                'name' => 'tiempo',
                'label' => "Tiempo",
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
                'label' => "Dias Sancion",
                'type' => "number",
            ]
        );
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
