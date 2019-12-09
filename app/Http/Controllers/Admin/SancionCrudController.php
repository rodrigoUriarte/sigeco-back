<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SancionRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SancionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SancionCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Sancion');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/sancion');
        $this->crud->setEntityNameStrings('sancion', 'sanciones');

        $this->crud->denyAccess(['create', 'update','delete','list','show']);

        if (backpack_user()->hasPermissionTo('createSancion')) {
            $this->crud->allowAccess('create');
        }
        if (backpack_user()->hasPermissionTo('updateSancion')) {
            $this->crud->allowAccess('update');
        }
        if (backpack_user()->hasPermissionTo('deleteSancion')) {
            $this->crud->allowAccess('delete');
        }
        if (backpack_user()->hasPermissionTo('listSancion')) {
            $this->crud->allowAccess('list');
        }
        if (backpack_user()->hasPermissionTo('showSancion')) {
            $this->crud->allowAccess('show');
        }

        //Si el usuario tiene rol de comensal solo mostrar sus entradas
        if (backpack_user()->hasRole('comensal')) {
            $this->crud->addClause('where', 'user_id', '=', backpack_user()->id);
        }
        //SI el usuario es un admin muestra solo los insumos del comedor del cual es responsable
        if (backpack_user()->hasRole('operativo')) {
            $this->crud->addClause('where', 'comedor_id', '=', backpack_user()->persona->comedor_id);

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
                'model' => "App\Models\BackpackUser", // foreign key model
                'searchLogic' => function ($query, $column, $searchTerm) {
                    $query->orWhereHas('user', function ($q) use ($column, $searchTerm) {
                        $q->where('name', 'like', '%' . $searchTerm . '%');
                        //->orWhereDate('fecha_inicio', '=', date($searchTerm));
                    });
                },
            ]);
        }

        $this->crud->addColumns(['desde', 'hasta', 'regla']);

        $this->crud->setColumnDetails('desde', [
            'name' => "desde", // The db column name
            'label' => "Desde", // Table column heading
            'type' => "date",
            // 'format' => 'l j F Y', // use something else than the base.default_date_format config value
        ]);

        $this->crud->setColumnDetails('hasta', [
            'name' => "hasta", // The db column name
            'label' => "Hasta", // Table column heading
            'type' => "date",
            // 'format' => 'l j F Y', // use something else than the base.default_date_format config value
        ]);

        $this->crud->setColumnDetails('regla', [
            'label' => 'Regla',
            'type' => 'select',
            'name' => 'regla_id', // the db column for the foreign key
            'entity' => 'regla', // the method that defines the relationship in your Model
            'attribute' => 'descripcion', // foreign key attribute that is shown to user
            'model' => "App\Models\Regla", // foreign key model
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('regla', function ($q) use ($column, $searchTerm) {
                    $q->where('descripcion', 'like', '%' . $searchTerm . '%');
                    //->orWhereDate('fecha_inicio', '=', date($searchTerm));
                });
            },
        ]);

    }

    protected function setupCreateOperation()
    {
        //$this->crud->setValidation(SancionRequest::class);

    }

    protected function setupUpdateOperation()
    {
        //$this->setupCreateOperation();
    }

    protected function setupShowOperation()
    {
        $this->crud->set('show.setFromDb', false);
        $this->setupListOperation();
    }
}
