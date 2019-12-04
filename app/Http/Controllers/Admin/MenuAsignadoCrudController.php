<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MenuAsignadoRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Carbon;

/**
 * Class MenuAsignadoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class MenuAsignadoCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;


    public function setup()
    {
        $this->crud->setModel('App\Models\MenuAsignado');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/menuAsignado');
        $this->crud->setEntityNameStrings('Menu Asignado', 'Menus Asignados');

        //Si el usuario tiene rol de comensal solo mostrar sus entradas
        if (backpack_user()->hasRole('comensal')) {
            $this->crud->addClause('where', 'user_id', '=', backpack_user()->id);
        }
        //SI el usuario es un admin muestra solo los insumos del comedor del cual es responsable
        if (backpack_user()->hasRole('admin')) {
            $this->crud->addClause('where', 'comedor_id', '=', backpack_user()->persona->comedor_id);
            $this->crud->denyAccess(['create', 'update','delete']);
        }
    }

    protected function setupListOperation()
    {
        //Si el usuario tiene rol de admin mostrar a que usuario corresponde cada menu asignado
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

        $this->crud->addColumns(['menu', 'fecha_inicio', 'fecha_fin']);

        $this->crud->setColumnDetails('menu', [
            'label' => 'Menu',
            'type' => 'select',
            'name' => 'menu_id', // the db column for the foreign key
            'entity' => 'menu', // the method that defines the relationship in your Model
            'attribute' => 'descripcion', // foreign key attribute that is shown to user
            'model' => "App\Models\Menu", // foreign key model
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('menu', function ($q) use ($column, $searchTerm) {
                    $q->where('descripcion', 'like', '%' . $searchTerm . '%');
                    //->orWhereDate('fecha_inicio', '=', date($searchTerm));
                });
            },
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(MenuAsignadoRequest::class);

        $this->crud->addField([
            'name'  => 'user_id',
            'type'  => 'hidden',
            'value' => backpack_user()->id,
        ]);

        $this->crud->addField([
            'name'  => 'comedor_id',
            'type'  => 'hidden',
            'value' => backpack_user()->persona->comedor_id,
        ]);

        $this->crud->addField([  // Select2
            'label' => "Menu",
            'type' => 'select2',
            'name' => 'menu_id', // the db column for the foreign key
            'entity' => 'menu', // the method that defines the relationship in your Model
            'attribute' => 'descripcion', // foreign key attribute that is shown to user
            'model' => "App\Models\Menu", // foreign key model
            'default' => 0, // set the default value of the select2
            'options'   => (function ($query) {
                return $query->where('comedor_id', backpack_user()->persona->comedor_id)->get();
            }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
        ]);

        $this->crud->addField([   // date_picker
            'name' => 'fecha_inicio',
            'type' => 'date_picker',
            'label' => 'Fecha Inicio',
            // optional:
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format' => 'dd-mm-yyyy',
                'language' => 'es',
                //'startDate' => Carbon::now(),
            ],
            //'default' => $fecha,
        ]);

        $this->crud->addField([   // date_picker
            'name' => 'fecha_fin',
            'type' => 'date_picker',
            'label' => 'Fecha Fin',
            // optional:
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format' => 'dd-mm-yyyy',
                'language' => 'es',
                //'startDate' => Carbon::now(),
            ],
            //'default' => $fecha,
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
