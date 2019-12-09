<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UnidadAcademicaRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class UnidadAcademicaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class UnidadAcademicaCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\UnidadAcademica');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/unidadAcademica');
        $this->crud->setEntityNameStrings('Unidad Academica', 'Unidades Academicas');

        $this->crud->denyAccess(['create', 'update','delete','list','show']);

        if (backpack_user()->hasPermissionTo('createUnidadAcademica')) {
            $this->crud->allowAccess('create');
        }
        if (backpack_user()->hasPermissionTo('updateUnidadAcademica')) {
            $this->crud->allowAccess('update');
        }
        if (backpack_user()->hasPermissionTo('deleteUnidadAcademica')) {
            $this->crud->allowAccess('delete');
        }
        if (backpack_user()->hasPermissionTo('listUnidadAcademica')) {
            $this->crud->allowAccess('list');
        }
        if (backpack_user()->hasPermissionTo('showUnidadAcademica')) {
            $this->crud->allowAccess('show');
        }
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns(['nombre']);

        $this->crud->setColumnDetails('nombre', [
            'name' => "nombre", // The db column name
            'label' => "Nombre", // Table column heading
            'type' => "text",
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(UnidadAcademicaRequest::class);

        $this->crud->addField([
            'name' => 'nombre',
            'type' => 'text',
            'label' => 'Nombre'
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
