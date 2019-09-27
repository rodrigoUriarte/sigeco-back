<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BandaHorariaRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class BandaHorariaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class BandaHorariaCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\BandaHoraria');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/bandaHoraria');
        $this->crud->setEntityNameStrings('Banda Horaria', 'Bandas Horarias');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns(['descripcion', 'hora_inicio', 'hora_fin', 'limite_comensales']);

    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(BandaHorariaRequest::class);

        $this->crud->addColumns(['descripcion', 'hora_inicio', 'hora_fin', 'limite_comensales']);

        $this->crud->addField([
            'name' => 'descripcion',
            'type' => 'text',
            'label' => 'Descripcion'
        ]);

        $this->crud->addField([
            'name' => 'hora_inicio',
            'label' => 'Hora Inicio',
            'type' => 'time'
        ]);

        $this->crud->addField([
            'name' => 'hora_fin',
            'label' => 'Hora Fin',
            'type' => 'time'
        ]);

        $this->crud->addField([
            'name' => 'limite_comensales',
            'type' => 'text',
            'label' => 'Limite Comensales'
        ]);

    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
