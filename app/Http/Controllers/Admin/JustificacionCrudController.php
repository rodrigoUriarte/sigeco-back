<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\JustificacionRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class JustificacionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class JustificacionCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Justificacion');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/justificacion');
        $this->crud->setEntityNameStrings('justificacion', 'justificaciones');

        $this->crud->denyAccess(['create', 'update', 'delete', 'list', 'show']);

        if (backpack_user()->hasPermissionTo('createJustificacion')) {
            $this->crud->allowAccess('create');
        }
        if (backpack_user()->hasPermissionTo('updateJustificacion')) {
            $this->crud->allowAccess('update');
        }
        if (backpack_user()->hasPermissionTo('deleteJustificacion')) {
            $this->crud->allowAccess('delete');
        }
        if (backpack_user()->hasPermissionTo('listJustificacion')) {
            $this->crud->allowAccess('list');
        }
        if (backpack_user()->hasPermissionTo('showJustificacion')) {
            $this->crud->allowAccess('show');
        }

        if (backpack_user()->hasRole('operativo')) {
            $this->crud->addClause('whereHas', 'asistencia', function ($query) {
                $query->where('comedor_id', backpack_user()->persona->comedor_id);
            });
        }
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns(['comensal', 'fecha', 'descripcion', 'documento']);

        $this->crud->setColumnDetails('comensal', [
            'label' => 'Comensal',
            'type' => 'select',
            'name' => 'asistencia_id', // the db column for the foreign key
            'entity' => 'asistencia', // the method that defines the relationship in your Model
            'attribute' => 'comensal', // foreign key attribute that is shown to user
            'model' => "App\Models\Asistencia", // foreign key model
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('asistencia.inscripcion.user', function ($q) use ($column, $searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%');
                });
            },
        ]);

        $this->crud->setColumnDetails('fecha', [
            'label' => 'Fecha Inscripcion',
            'type' => 'select',
            'name' => 'asistencia_id', // the db column for the foreign key
            'entity' => 'asistencia', // the method that defines the relationship in your Model
            'attribute' => 'fecha_inscripcion_formato', // foreign key attribute that is shown to user
            'model' => "App\Models\Asistencia", // foreign key model
            // 'searchLogic' => function ($query, $column, $searchTerm) {
            //     $query->orWhereHas('asistencia.inscripcion', function ($q) use ($column, $searchTerm) {
            //         $q->where('fecha_inscripcion', 'like', '%' . $searchTerm . '%');
            //     });
            // },
        ]);

        $this->crud->setColumnDetails('descripcion', [
            'name' => 'descripcion', // The db column name
            'label' => "Descripcion Justificacion", // Table column heading
            // 'prefix' => "Name: ",
            // 'suffix' => "(user)",
            // 'limit' => 120, // character limit; default is 50;
        ]);

        $this->crud->setColumnDetails('documento', [
            'name' => 'documento', // The db column name
            'label' => "Documento Justificativo", // Table column heading
            'type' => 'image',
            //'prefix' => 'documentos/justificaciones/',
            // image from a different disk (like s3 bucket)
            'disk' => 'public', 
            // optional width/height if 25px is not ok with you
            // 'height' => '30px',
            // 'width' => '30px',
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(JustificacionRequest::class);

        $this->crud->addField(
            [ // Date
                'name' => 'fecha_busqueda',
                'label' => 'Fecha Inasistencia',
                'type' => 'date'
            ]
        );
        $this->crud->addField(
            [  // Select2
                'label' => "Comensal",
                'type' => "select2_from_ajax",
                'name' => 'asistencia_id', // the db column for the foreign key
                'entity' => 'asistencia', // the method that defines the relationship in your Model
                'attribute' => 'comensal', // foreign key attribute that is shown to user
                'model' => "App\Models\Asistencia", // foreign key model
                'data_source' => url("admin/calculoInasistenciasFecha"), // url to controller search function (with /{id} should return model)
                'placeholder' => 'Seleccione un comensal', // placeholder for the select
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'dependencies' => ['fecha_busqueda'],
                'include_all_form_fields' => 'fecha_busqueda',
                //SE RESETEA SOLO SI EL MENU CAMBIA NO CUANDO CAMBIA LA FECHA, A CORREGIR
            ]
        );
        $this->crud->addField(
            [
                'name' => 'descripcion',
                'label' => 'Descripcion Justificacion',
                'type' => 'text'
            ]
        );
        $this->crud->addField([   // Upload
            'name'      => 'documento',
            'label'     => 'Documento',
            'type'      => 'upload',
            'upload'    => true,
            //'disk'      => 'local', // if you store files in the /public folder, please ommit this; if you store them in /storage or S3, please specify it;
            // optional:
            //'temporary' => 10 // if using a service, such as S3, that requires you to make temporary URL's this will make a URL that is valid for the number of minutes specified
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
