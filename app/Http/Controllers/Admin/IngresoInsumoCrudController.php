<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\IngresoInsumoRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Carbon;


/**
 * Class IngresoInsumoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class IngresoInsumoCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\IngresoInsumo');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/ingresoInsumo');
        $this->crud->setEntityNameStrings('Ingreso Insumo', 'Ingresos Insumos');

        //SI el usuario es un admin muestra solo los ingresos de insumos del comedor del cual es responsable
        if (backpack_user()->hasRole('admin')) {
            $this->crud->addClause('where', 'comedor_id', '=', backpack_user()->persona->comedor_id);
        }

        if (backpack_user()->hasRole('comensal')) {
            $this->crud->denyAccess(['create', 'update','delete','list','show']);
        }
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns(['user_id', 'insumo_id', 'fecha_vencimiento', 'cantidad','creado']);

        $this->crud->setColumnDetails('user_id', [
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

        $this->crud->setColumnDetails('insumo_id', [
            'label' => 'Insumo',
            'type' => 'select',
            'name' => 'insumo_id', // the db column for the foreign key
            'entity' => 'insumo', // the method that defines the relationship in your Model
            'attribute' => 'descripcion', // foreign key attribute that is shown to user
            'model' => "App\Models\Insumo", // foreign key model
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('insumo', function ($q) use ($column, $searchTerm) {
                    $q->where('descripcion', 'like', '%' . $searchTerm . '%');
                    //->orWhereDate('fecha_inicio', '=', date($searchTerm));
                });
            },
        ]);

        $this->crud->setColumnDetails('creado', [
            'name' => "created_at", // The db column name
            'label' => "Fecha Ingreso", // Table column heading
            'type' => "datetime",
             // 'format' => 'l j F Y', // use something else than the base.default_date_format config value
         ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(IngresoInsumoRequest::class);

        $this->crud->addField(
            [  // Select2
                'label' => "Insumo",
                'type' => 'select2',
                'name' => 'insumo_id', // the db column for the foreign key
                'entity' => 'insumo', // the method that defines the relationship in your Model
                'attribute' => 'descripcionUM', // foreign key attribute that is shown to user
                'model' => "App\Models\Insumo", // foreign key model
                'default' => 0, // set the default value of the select2
                'options'   => (function ($query) {
                    return $query->where('comedor_id', backpack_user()->persona->comedor_id)->get();
                }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
            ]
        );
        $this->crud->addField([   // date_picker
            'name' => 'fecha_vencimiento',
            'type' => 'date_picker',
            'label' => 'Fecha Vencimiento',
            // optional:
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format' => 'dd-mm-yyyy',
                'language' => 'es',
                'startDate' => Carbon::now(),
                'defaultViewDate' => Carbon::now(),
            ],
            'default' => Carbon::now()->toDateString(),
        ]);
        $this->crud->addField(
            [
                'name' => 'cantidad',
                'label' => "Cantidad",
                'type' => "number",
            ]
        );
        $this->crud->addField([
            'name'  => 'comedor_id',
            'type'  => 'hidden',
            'value' => backpack_user()->persona->comedor_id,
        ]);
        $this->crud->addField([
            'name'  => 'user_id',
            'type'  => 'hidden',
            'value' => backpack_user()->id,
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
