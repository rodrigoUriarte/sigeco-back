<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AsistenciaRequest;
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

        //SI el usuario es un admin muestra solo las asistencias del comedor del cual es responsable
        if (backpack_user()->hasRole('admin')) {
            $this->crud->addClause('where', 'comedor_id', '=', backpack_user()->persona->comedor_id);
        }
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns(['inscripcion', 'asistio', 'fecha_asistencia']);

        $this->crud->setColumnDetails('inscripcion', [
            //NO FUNCIONA LA BUSQUEDA POR EL ATRIBUTO DE LA INSCRIPCION
            'label' => 'Comensal',
            'type' => 'select',
            'name' => 'inscripcion_id', // the db column for the foreign key
            'entity' => 'inscripcion', // the method that defines the relationship in your Model
            'attribute' => 'nombreInscripcion', // foreign key attribute that is shown to user
            'model' => "App\Models\Inscripcion", // foreign key model
            // 'searchLogic' => function ($query, $column, $searchTerm) {
            //     $query->orWhereHas('inscripcion', function ($q) use ($column, $searchTerm) {
            //         $q->where('nombreInscripcion', 'like', '%' . $searchTerm . '%');
            //     });
            // },
        ]);

        $this->crud->setColumnDetails('asistio',[
            'name' => 'asistio',
            'label' => 'Asistio',
            'type' => 'boolean',
            // optionally override the Yes/No texts
            'options' => [0 => 'NO', 1 => 'SI']
        ]);

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
                'attribute' => 'nombreInscripcion', // foreign key attribute that is shown to user
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
        $this->setupCreateOperation();
    }
}
