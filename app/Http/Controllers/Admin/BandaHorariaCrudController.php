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

        if (backpack_user()->hasRole('admin')) {
            $this->crud->addClause('where', 'comedor_id', '=', 
            backpack_user()->persona->comedor_id);
        }
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns(['comedor','descripcion', 'hora_inicio', 'hora_fin', 'limite_comensales']);

        $this->crud->setColumnDetails('comedor', [
            'label' => 'Comedor',
            'type' => 'select',
            'name' => 'comedor_id', // the db column for the foreign key
            'entity' => 'comedor', // the method that defines the relationship in your Model
            'attribute' => 'descripcion', // foreign key attribute that is shown to user
            'model' => "App\Models\Comedor", // foreign key model
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('comedor', function ($q) use ($column, $searchTerm) {
                    $q->where('descripcion', 'like', '%' . $searchTerm . '%');
                    //->orWhereDate('fecha_inicio', '=', date($searchTerm));
                });
            },
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
            'type' => 'text',
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
