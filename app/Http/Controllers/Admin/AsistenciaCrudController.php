<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AsistenciaRequest;
use App\Http\Requests\UpdateAsistenciaRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Carbon;

/**
 * Class AsistenciaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AsistenciaCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Asistencia');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/asistencia');
        $this->crud->setEntityNameStrings('asistencia', 'asistencias');

        $this->crud->denyAccess(['create', 'update', 'delete', 'list', 'show']);

        if (backpack_user()->hasPermissionTo('createAsistencia')) {
            $this->crud->allowAccess('create');
        }
        if (backpack_user()->hasPermissionTo('updateAsistencia')) {
            $this->crud->allowAccess('update');
        }
        if (backpack_user()->hasPermissionTo('deleteAsistencia')) {
            $this->crud->allowAccess('delete');
        }
        if (backpack_user()->hasPermissionTo('listAsistencia')) {
            $this->crud->allowAccess('list');
        }
        if (backpack_user()->hasPermissionTo('showAsistencia')) {
            $this->crud->allowAccess('show');
        }

        //Si el usuario tiene rol de comensal solo mostrar sus entradas
        if (backpack_user()->hasRole('comensal')) {
            $this->crud->addClause('whereHas', 'inscripcion', function ($query) {
                $query->where('user_id', backpack_user()->id);
            });
        }

        //SI el usuario es un admin muestra solo las asistencias del comedor del cual es responsable
        if (backpack_user()->hasRole('operativo')) {
            $this->crud->addClause('where', 'comedor_id', '=', backpack_user()->persona->comedor_id);
        }
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns(['fecha_inscripcion', 'asistio', 'fecha_asistencia']);

        $this->crud->setColumnDetails('fecha_inscripcion', [
            //NO FUNCIONA LA BUSQUEDA POR EL ATRIBUTO DE LA INSCRIPCION
            'label' => 'Fecha Inscripcion',
            'type' => 'select',
            'name' => 'inscripcion_id', // the db column for the foreign key
            'entity' => 'inscripcion', // the method that defines the relationship in your Model
            'attribute' => 'fecha_inscripcion_formato', // foreign key attribute that is shown to user
            'model' => "App\Models\Inscripcion", // foreign key model
            // 'searchLogic' => function ($query, $column, $searchTerm) {
            //     $query->orWhereHas('inscripcion', function ($q) use ($column, $searchTerm) {
            //         $q->where('fecha_inscripcion_formato', 'like', '%' . $searchTerm . '%');
            //     });
            // },
        ]);

        $this->crud->setColumnDetails('asistio', [
            'name' => 'asistio',
            'label' => 'Asistio',
            'type' => 'boolean',
            // optionally override the Yes/No texts
            'options' => [0 => 'NO', 1 => 'SI']
        ]);

        $this->crud->setColumnDetails('fecha_asistencia', [
            'name' => "fecha_asistencia", // The db column name
            'label' => "Fecha Asistencia", // Table column heading
            'type' => "datetime",
            // 'format' => 'l j F Y', // use something else than the base.default_date_format config value
        ]);

        if (backpack_user()->hasRole('operativo')) {

            $this->crud->addColumns(['comensal']);

            $this->crud->setColumnDetails('comensal', [
                'label' => 'Comensal',
                'type' => 'select',
                'name' => 'inscripcion_id', // the db column for the foreign key
                'entity' => 'inscripcion', // the method that defines the relationship in your Model
                'attribute' => "nombre", // foreign key attribute that is shown to user
                'model' => "App\Models\Inscripcion", // foreign key model
                // 'searchLogic' => function ($query, $column, $searchTerm) {
                //     $query->orWhereHas('inscripcion', function ($q) use ($column, $searchTerm) {
                //         $q->where('nombre', 'like', '%' . $searchTerm . '%');
                //     });
                // },
            ]);
        }

        // daterange filter
        $this->crud->addFilter(
            [
                'type'  => 'custom_date_range',
                'name'  => 'from_to',
                'label' => 'Fecha Inscripcion'
            ],
            false,
            function ($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('whereHas', 'inscripcion', function ($query) use ($dates) {
                    $query->where('fecha_inscripcion', '>=', $dates->from)
                        ->where('fecha_inscripcion', '<=', $dates->to . ' 23:59:59');
                });
            }
        );

        // add a "simple" filter called Draft
        $this->crud->addFilter(
            [
                'type' => 'simple',
                'name' => 'asistio',
                'label' => 'Asistio'
            ],
            false, // the simple filter has no values, just the "Draft" label specified above
            function () { // if the filter is active (the GET parameter "draft" exits)
                $this->crud->addClause('where', 'asistio', '1');
                // we've added a clause to the CRUD so that only elements with draft=1 are shown in the table
                // an alternative syntax to this would have been
                // $this->crud->query = $this->crud->query->where('draft', '1'); 
                // another alternative syntax, in case you had a scopeDraft() on your model:
                // $this->crud->addClause('draft'); 
            }
        );

        // add a "simple" filter called Draft
        $this->crud->addFilter(
            [
                'type' => 'simple',
                'name' => 'noasistio',
                'label' => 'No Asistio'
            ],
            false, // the simple filter has no values, just the "Draft" label specified above
            function () { // if the filter is active (the GET parameter "draft" exits)
                $this->crud->addClause('where', 'asistio', '0');
                // we've added a clause to the CRUD so that only elements with draft=1 are shown in the table
                // an alternative syntax to this would have been
                // $this->crud->query = $this->crud->query->where('draft', '1'); 
                // another alternative syntax, in case you had a scopeDraft() on your model:
                // $this->crud->addClause('draft'); 
            }
        );
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(AsistenciaRequest::class);

        $this->crud->addField(
            [  // Select2
                'label' => "Comensal",
                'type' => 'select2',
                'name' => 'inscripcion_id', // the db column for the foreign key
                'entity' => 'inscripcion', // the method that defines the relationship in your Model
                'attribute' => 'nombre', // foreign key attribute that is shown to user
                'model' => "App\Models\Inscripcion", // foreign key model
                'default' => 0, // set the default value of the select2
                'options'   => (function ($query) {
                    return $query
                        ->where('comedor_id', backpack_user()->persona->comedor_id)
                        ->where('fecha_inscripcion', Carbon::now()->toDateString())
                        ->get();
                }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
            ]
        );

        $this->crud->addField([
            'name'  => 'comedor_id',
            'type'  => 'hidden',
            'value' => backpack_user()->persona->comedor_id,
        ]);

        $this->crud->addField([
            'name'  => 'asistio',
            'type'  => 'hidden',
            'value' => true,
        ]);

        $this->crud->addField([
            'name'  => 'fecha_asistencia',
            'type'  => 'hidden',
            'value' => Carbon::now(),
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->crud->setValidation(UpdateAsistenciaRequest::class);

        $this->crud->addField([
            'name' => 'asistio',
            'label' => 'Asistencia fuera BH',
            'type' => 'checkbox'
        ]);
    }


    protected function setupShowOperation()
    {
        $this->crud->set('show.setFromDb', false);
        $this->setupListOperation();
    }
}
