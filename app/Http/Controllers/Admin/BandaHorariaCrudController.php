<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BandaHorariaRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Carbon;

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

        $this->crud->denyAccess(['create', 'update', 'delete', 'list', 'show']);

        if (backpack_user()->hasPermissionTo('createBandaHoraria')) {
            $this->crud->allowAccess('create');
        }
        if (backpack_user()->hasPermissionTo('updateBandaHoraria')) {
            $this->crud->allowAccess('update');
        }
        if (backpack_user()->hasPermissionTo('deleteBandaHoraria')) {
            $this->crud->allowAccess('delete');
        }
        if (backpack_user()->hasPermissionTo('listBandaHoraria')) {
            $this->crud->allowAccess('list');
        }
        if (backpack_user()->hasPermissionTo('showBandaHoraria')) {
            $this->crud->allowAccess('show');
        }

        if (backpack_user()->hasRole('operativo')) {
            $this->crud->addClause(
                'where',
                'comedor_id',
                '=',
                backpack_user()->persona->comedor_id
            );
        }
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns(['descripcion', 'hora_inicio', 'hora_fin', 'limite_comensales']);

        $this->crud->setColumnDetails('descripcion', [
            'name' => "descripcion", // The db column name
            'label' => "Descripcion", // Table column heading
            'type' => "text",
        ]);
        $this->crud->setColumnDetails('hora_inicio', [
            'name' => "hora_inicio", // The db column name
            'label' => "Hora Inicio", // Table column heading
            'type' => "text",
        ]);
        $this->crud->setColumnDetails('hora_fin', [
            'name' => "hora_fin", // The db column name
            'label' => "Hora Fin", // Table column heading
            'type' => "text",
        ]);
        $this->crud->setColumnDetails('limite_comensales', [
            'name' => "limite_comensales", // The db column name
            'label' => "Limite Comensales", // Table column heading
            'type' => "number",
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(BandaHorariaRequest::class);

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
            'type' => 'number',
            'label' => 'Limite Comensales'
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
