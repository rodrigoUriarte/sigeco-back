<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\InscripcionRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class InscripcionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class InscripcionCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Inscripcion');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/inscripcion');
        $this->crud->setEntityNameStrings('inscripcion', 'inscripciones');

        //Si el usuario tiene rol de comensal solo mostrar sus entradas
        if (backpack_user()->hasRole('comensal')) {
            $this->crud->addClause('where', 'user_id', '=', backpack_user()->id);
        }
    }

    protected function setupListOperation()
    {

        //Si el usuario tiene rol de admin mostrar a que usuario corresponde cada inscripcion
        if (backpack_user()->hasRole('admin')) {

            $this->crud->addColumns(['usuario']);

            $this->crud->setColumnDetails('usuario', [
                'label' => 'Usuario',
                'type' => 'select',
                'name' => 'user_id', // the db column for the foreign key
                'entity' => 'user', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model' => "App\Models\BackpackUser", // foreign key model
                'searchLogic' => function ($query, $column, $searchTerm) {
                    $query->orWhereHas('user', function ($q) use ($column, $searchTerm) {
                        $q->where('name', 'like', '%' . $searchTerm . '%');
                        //->orWhereDate('fecha_inicio', '=', date($searchTerm));
                    });
                },
            ]);
        }

        $this->crud->addColumns(['fecha_inscripcion', 'banda_horaria', 'menu_asignado', 'fecha_asistencia']);

        $this->crud->setColumnDetails('banda_horaria', [
            'label' => 'Banda Horaria',
            'type' => 'select',
            'name' => 'banda_horaria_id', // the db column for the foreign key
            'entity' => 'banda_horaria', // the method that defines the relationship in your Model
            'attribute' => 'descripcion', // foreign key attribute that is shown to user
            'model' => "App\Models\BandaHoraria", // foreign key model
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('banda_horaria', function ($q) use ($column, $searchTerm) {
                    $q->where('descripcion', 'like', '%' . $searchTerm . '%');
                    //->orWhereDate('fecha_inicio', '=', date($searchTerm));
                });
            },
        ]);

        $this->crud->setColumnDetails('menu_asignado', [

            'label' => 'Menu Asignado',
            'type' => 'select',
            'name' => 'menu_asignado_id', // the db column for the foreign key
            'entity' => 'menu_asignado', // the method that defines the relationship in your Model
            'attribute' => 'descripcion', // foreign key attribute that is shown to user
            'model' => "App\Models\Menu", // foreign key model
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('menu_asignado', function ($q) use ($column, $searchTerm) {
                    $q->where('menu_id', 'like', '%' . $searchTerm . '%');
                    //->orWhereDate('fecha_inicio', '=', date($searchTerm));
                });
            },
        ]);

    }

    protected function setupCreateOperation()
    {


        $this->crud->setValidation(InscripcionRequest::class);

        $this->crud->addField([
            'name'  => 'user_id',
            'type'  => 'hidden',
            'value' => backpack_user()->id,
        ]);

        $this->crud->addField([
            'name' => 'fecha_inscripcion',
            'label' => 'Fecha Inscripcion',
            'type' => 'date'
        ]);
        $this->crud->addField([
            'label' => "Banda Horaria",
            'type' => 'select2',
            'name' => 'banda_horaria_id', // the db column for the foreign key
            'entity' => 'banda_horaria', // the method that defines the relationship in your Model
            'attribute' => 'descripcion', // foreign key attribute that is shown to user
            'model' => "App\Models\BandaHoraria", // foreign key model
            // optional
            'default' => 1, // set the default value of the select2
        ]);

    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
