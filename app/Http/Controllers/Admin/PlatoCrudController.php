<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PlatoRequest;
use App\Models\Plato;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Redirect;
use Prologue\Alerts\Facades\Alert;

/**
 * Class PlatoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PlatoCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Plato');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/plato');
        $this->crud->setEntityNameStrings('plato', 'platos');

        $this->crud->denyAccess(['create', 'update', 'delete', 'list', 'show']);

        if (backpack_user()->hasPermissionTo('createPlato')) {
            $this->crud->allowAccess('create');
        }
        if (backpack_user()->hasPermissionTo('updatePlato')) {
            $this->crud->allowAccess('update');
        }
        if (backpack_user()->hasPermissionTo('deletePlato')) {
            $this->crud->allowAccess('delete');
        }
        if (backpack_user()->hasPermissionTo('listPlato')) {
            $this->crud->allowAccess('list');
        }
        if (backpack_user()->hasPermissionTo('showPlato')) {
            $this->crud->allowAccess('show');
        }

        //SI el usuario es un admin muestra solo los platos del comedor del cual es responsable
        if (backpack_user()->hasRole('operativo')) {
            $this->crud->addClause('where', 'comedor_id', '=', backpack_user()->persona->comedor_id);
        }
    }

    protected function setupListOperation()
    {
        $this->crud->hasAccessOrFail('list');
        $this->crud->addColumns(['menu', 'descripcion']);

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

        $this->crud->setColumnDetails('descripcion', [
            'name' => "descripcion", // The db column name
            'label' => "Descripcion", // Table column heading
            'type' => "text",
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->hasAccessOrFail('create');
        $this->crud->setValidation(PlatoRequest::class);

        $this->crud->addField([
            'name'  => 'comedor_id',
            'type'  => 'hidden',
            'value' => backpack_user()->persona->comedor_id,
        ]);

        $this->crud->addField(
            [  // Select2
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
            ]
        );
        $this->crud->addField(
            [
                'label' => "Descripcion",
                'type' => 'text',
                'name' => 'descripcion',
            ]
        );
        $this->crud->addField(
            [
                'label' => "Insumos",
                'type' => 'repeatable',
                'name' => 'insumos',
                'fields' => [
                    [
                        'name' => 'insumos', //the name of the relation
                        'multiple' => false //this is mandatory.
                    ],
                    [
                        'name' => 'cantidad',
                        'type' => 'text',
                        'label' => 'Cantidad'
                    ],
                ],
            ]
        );
    }

    protected function setupUpdateOperation()
    {
        $this->crud->hasAccessOrFail('update');
        $this->setupCreateOperation();
    }

    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $plato= Plato::findOrFail($id);

        if ($plato->platosAsignados()->exists()) {
            Alert::error('Este plato tiene asociado platos asignados. No se puede eliminar');
            return Alert::getMessages();
        }else {
            $plato->insumos()->detach();
        }

        return $this->crud->delete($id);
    }

    protected function setupShowOperation()
    {
        $this->crud->hasAccessOrFail('show');
        $this->crud->set('show.setFromDb', false);
        $this->setupListOperation();

        $this->crud->addColumns(['insumos']);

        $this->crud->setColumnDetails('insumos', [
            // n-n relationship (with pivot table)
            'label' => "Insumos", // Table column heading
            'type' => "select_multiple_rowStyle",
            'name' => 'insumos', // the method that defines the relationship in your Model
            'entity' => 'insumos', // the method that defines the relationship in your Model
            'attribute' => "descripcion_cantidad", // foreign key attribute that is shown to user
            'model' => "App\Models\Insumo", // foreign key model
         ]);
    }
}
