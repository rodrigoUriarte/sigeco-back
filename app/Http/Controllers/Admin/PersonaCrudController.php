<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PersonaRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

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
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns(['DNI', 'Nombre', 'Apellido', 'Telefono']);

        $this->crud->setColumnDetails('DNI', [
            'label' => 'DNI',
            'name' => 'id',
        ]);
        $this->crud->setColumnDetails('Nombre', [
            'label' => 'Nombre',
            'name' => 'nombre',
        ]);
        $this->crud->setColumnDetails('Apellido', [
            'label' => 'Apellido',
            'name' => 'apellido',
        ]);
        $this->crud->setColumnDetails('Telefono', [
            'label' => 'Telefono',
            'name' => 'telefono',
        ]);
        
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(PersonaRequest::class);

        $this->crud->addField([
            'name' => 'id',
            'type' => 'text',
            'label' => 'DNI'
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
        $this->crud->addField([
            'name' => 'telefono',
            'type' => 'text',
            'label' => 'Telelfono'
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
