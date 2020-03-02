<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MenuAsignadoRequest;
use App\Models\MenuAsignado;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Prologue\Alerts\Facades\Alert;

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
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation {
        destroy as traitDestroy;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;


    public function setup()
    {
        $this->crud->setModel('App\Models\MenuAsignado');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/menuAsignado');
        $this->crud->setEntityNameStrings('Menu Asignado', 'Menus Asignados');

        $this->crud->denyAccess(['create', 'update', 'delete', 'list', 'show']);

        if (backpack_user()->hasPermissionTo('createMenuAsignado')) {
            $this->crud->allowAccess('create');
        }
        if (backpack_user()->hasPermissionTo('updateMenuAsignado')) {
            $this->crud->allowAccess('update');
        }
        if (backpack_user()->hasPermissionTo('deleteMenuAsignado')) {
            $this->crud->allowAccess('delete');
        }
        if (backpack_user()->hasPermissionTo('listMenuAsignado')) {
            $this->crud->allowAccess('list');
        }
        if (backpack_user()->hasPermissionTo('showMenuAsignado')) {
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
        //Si el usuario tiene rol de admin mostrar a que usuario corresponde cada menu asignado
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
                });
            },
        ]);

        $this->crud->setColumnDetails('fecha_inicio', [
            'name' => "fecha_inicio", // The db column name
            'label' => "Fecha Inicio", // Table column heading
            'type' => "date",
            // 'format' => 'l j F Y', // use something else than the base.default_date_format config value
        ]);

        $this->crud->setColumnDetails('fecha_fin', [
            'name' => "fecha_fin", // The db column name
            'label' => "Fecha Fin", // Table column heading
            'type' => "date",
            // 'format' => 'l j F Y', // use something else than the base.default_date_format config value
        ]);

        // daterange filter
        $this->crud->addFilter(
            [
                'type'  => 'es_date_range',
                'name'  => 'from_to',
                'label' => 'Fecha Inicio/Fin'
            ],
            false,
            function ($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('where', 'fecha_inicio', '>=', $dates->from);
                $this->crud->addClause('where', 'fecha_fin', '<=', $dates->to . ' 23:59:59');
            }
        );
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

    public function edit($id)
    {
        $this->crud->hasAccessOrFail('update');

        $hoy = Carbon::now();
        $ma = MenuAsignado::find($id);
        $fi = $ma->fecha_inicio;
        $fi = Carbon::parse($fi);

        $lma = $ma->comedor->parametro->limite_menu_asignado;
        if (Carbon::now()->daysInMonth < $lma) {
            $lma = Carbon::now()->daysInMonth - 1;
        } else {
            $lma = $lma - 1;
        }

        $fl = $fi->subMonth()->addDays($lma);
        if ($hoy > $fl) {
            Alert::info('No se puede editar un menu asignado despues de la fecha limite.')->flash();
            return Redirect::to('admin/menuAsignado');
        } else {
            $this->crud->applyConfigurationFromSettings('update');
            $this->crud->hasAccessOrFail('update');

            // get entry ID from Request (makes sure its the last ID for nested resources)
            $id = $this->crud->getCurrentEntryId() ?? $id;
            $this->crud->setOperationSetting('fields', $this->crud->getUpdateFields());

            // get the info for that entry
            $this->data['entry'] = $this->crud->getEntry($id);
            $this->data['crud'] = $this->crud;
            $this->data['saveAction'] = $this->crud->getSaveAction();
            $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.edit') . ' ' . $this->crud->entity_name;

            $this->data['id'] = $id;
            // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
            return view($this->crud->getEditView(), $this->data);
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
