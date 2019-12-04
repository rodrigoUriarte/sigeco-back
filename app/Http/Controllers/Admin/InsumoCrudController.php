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
        
        //SI el usuario es un admin muestra solo los insumos del comedor del cual es responsable
        if (backpack_user()->hasRole('admin')) {
            $this->crud->addClause('where', 'comedor_id', '=', backpack_user()->persona->comedor_id);
        }

        if (backpack_user()->hasRole('comensal')) {
            $this->crud->denyAccess(['create', 'update','delete','list','show']);
        }
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns(['descripcion','unidad_medida']);

    }

    protected function setupCreateOperation()
    {
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
        $this->setupCreateOperation();
    }

    protected function setupShowOperation()
    {
        $this->crud->set('show.setFromDb', false);
        $this->setupListOperation();
    }
}
