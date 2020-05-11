<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DiaPreferenciaRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class DiaPreferenciaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DiaPreferenciaCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\DiaPreferencia');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/diapreferencia');
        $this->crud->setEntityNameStrings('Dia Preferencia', 'Dias Preferencia');

        $this->crud->denyAccess(['create', 'update', 'delete', 'list', 'show']);

        if (backpack_user()->hasPermissionTo('createDiaPreferencia')) {
            $this->crud->allowAccess('create');
        }
        if (backpack_user()->hasPermissionTo('updateDiaPreferencia')) {
            $this->crud->allowAccess('update');
        }
        if (backpack_user()->hasPermissionTo('deleteDiaPreferencia')) {
            $this->crud->allowAccess('delete');
        }
        if (backpack_user()->hasPermissionTo('listDiaPreferencia')) {
            $this->crud->allowAccess('list');
        }
        if (backpack_user()->hasPermissionTo('showDiaPreferencia')) {
            $this->crud->allowAccess('show');
        }

        //Si el usuario tiene rol de comensal solo mostrar sus entradas
        if (backpack_user()->hasRole('comensal')) {
            $this->crud->addClause('where', 'user_id', '=', backpack_user()->id);
        }

        //SI el usuario es un admin muestra solo las asistencias del comedor del cual es responsable
        if (backpack_user()->hasRole('operativo')) {
            $this->crud->addClause('whereHas', 'user.persona', function ($query) {
                $query->where('comedor_id', backpack_user()->persona->comedor_id);
            });
        }
    }

    protected function setupListOperation()
    {
        //Si el usuario tiene rol de admin mostrar a que usuario corresponde cada inscripcion
        if (backpack_user()->hasRole('operativo')) {

            $this->crud->addColumns(['usuario']);

            $this->crud->setColumnDetails('usuario', [
                'label' => 'Usuario',
                'type' => 'select',
                'name' => 'user_id', // the db column for the foreign key
                'entity' => 'user', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model' => "App\User", // foreign key model
                'searchLogic' => function ($query, $column, $searchTerm) {
                    $query->orWhereHas('user', function ($q) use ($column, $searchTerm) {
                        $q->where('name', 'like', '%' . $searchTerm . '%');
                    });
                },
            ]);
        }

        $this->crud->addColumns(['dia_servicio', 'banda_horaria']);

        $this->crud->setColumnDetails('dia_servicio', [
            'label' => 'Dia de Servicio',
            'type' => 'select',
            'name' => 'dia_servicio_id', // the db column for the foreign key
            'entity' => 'diaServicio', // the method that defines the relationship in your Model
            'attribute' => 'dia', // foreign key attribute that is shown to user
            'model' => "App\Models\DiaServicio", // foreign key model
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('diaServicio', function ($q) use ($column, $searchTerm) {
                    $q->where('dia', 'like', '%' . $searchTerm . '%');
                });
            },
        ]);

        $this->crud->setColumnDetails('banda_horaria', [
            'label' => 'Banda Horaria',
            'type' => 'select',
            'name' => 'banda_horaria_id', // the db column for the foreign key
            'entity' => 'bandaHoraria', // the method that defines the relationship in your Model
            'attribute' => 'descripcion', // foreign key attribute that is shown to user
            'model' => "App\Models\BandaHoraria", // foreign key model
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('bandaHoraria', function ($q) use ($column, $searchTerm) {
                    $q->where('descripcion', 'like', '%' . $searchTerm . '%');
                });
            },
        ]);

        if (backpack_user()->persona->comedor->parametro->retirar == 1) {
            $this->crud->addColumns(['retira']);

            $this->crud->setColumnDetails('retira', [
                'name' => 'retira',
                'label' => 'Retira',
                'type' => 'boolean',
                // optionally override the Yes/No texts
                'options' => [0 => 'NO', 1 => 'SI']
            ]);
        }
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(DiaPreferenciaRequest::class);

        $this->crud->addField([
            'name'  => 'user_id',
            'type'  => 'hidden',
            'value' => backpack_user()->id,
        ]);

        $this->crud->addField(
            [
                'label' => "Dia de Servicio",
                'type' => 'select2',
                'name' => 'dia_servicio_id', // the db column for the foreign key
                'entity' => 'diaServicio', // the method that defines the relationship in your Model
                'attribute' => 'dia', // foreign key attribute that is shown to user
                'model' => "App\Models\DiaServicio", // foreign key model
                // optional
                'default' => 0, // set the default value of the select2
                'options'   => (function ($query) {
                    return $query->where('comedor_id', '=', backpack_user()->persona->comedor_id)->get();
                }),
            ]
        );

        $this->crud->addField(
            [
                'label' => "Banda Horaria",
                'type' => 'select2',
                'name' => 'banda_horaria_id', // the db column for the foreign key
                'entity' => 'bandaHoraria', // the method that defines the relationship in your Model
                'attribute' => 'descripcion', // foreign key attribute that is shown to user
                'model' => "App\Models\BandaHoraria", // foreign key model
                // optional
                'default' => 0, // set the default value of the select2
                'options'   => (function ($query) {
                    return $query->where('comedor_id', '=', backpack_user()->persona->comedor_id)->get();
                }),
            ]
        );

        if (backpack_user()->persona->comedor->parametro->retirar == 1) {
            $this->crud->addField([
                'name' => 'retira',
                'label' => 'Retira',
                'type' => 'checkbox'
            ]);
        } else {
            $this->crud->addField([
                'name'  => 'retira',
                'type'  => 'hidden',
                'value' => 0,
            ]);
        }
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
