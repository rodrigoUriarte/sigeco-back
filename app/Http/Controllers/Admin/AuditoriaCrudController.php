<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AuditoriaRequest;
use App\Models\Auditoria;
use App\Models\IngresoInsumo;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AuditoriaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AuditoriaCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Auditoria');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/auditoria');
        $this->crud->setEntityNameStrings('auditoria', 'auditorias');

        $this->crud->enableDetailsRow(); 
        $this->crud->allowAccess('details_row'); 
        $this->crud->setDetailsRowView('details_row.detalle_auditoria');


        $this->crud->denyAccess(['create', 'update','delete','list','show']);

        if (backpack_user()->hasPermissionTo('createAuditoria')) {
            $this->crud->allowAccess('create');
        }
        if (backpack_user()->hasPermissionTo('updateAuditoria')) {
            $this->crud->allowAccess('update');
        }
        if (backpack_user()->hasPermissionTo('deleteAuditoria')) {
            $this->crud->allowAccess('delete');
        }
        if (backpack_user()->hasPermissionTo('listAuditoria')) {
            $this->crud->allowAccess('list');
        }
        if (backpack_user()->hasPermissionTo('showAuditoria')) {
            $this->crud->allowAccess('show');
        }
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns(['user','event','modelo','valor_previo','valor_nuevo']);

        $this->crud->setColumnDetails('user', [
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

        $this->crud->setColumnDetails('event', [
            'name' => "event", // The db column name
            'label' => "Evento", // Table column heading
            'type' => "text",
        ]);

        $this->crud->setColumnDetails('modelo', [
            'name' => "auditable_type", // The db column name
            'label' => "Modelo", // Table column heading
            'type' => "text",
        ]);

        $this->crud->setColumnDetails('valor_previo', [
            'name' => "old_values", // The db column name
            'label' => "Valor Previo", // Table column heading
            'type' => "text",
        ]);

        $this->crud->setColumnDetails('valor_nuevo', [
            'name' => "new_values", // The db column name
            'label' => "Valor Nuevo", // Table column heading
            'type' => "text",
        ]);

    }

    public function showDetailsRow($id){

        $this->crud->hasAccessOrFail('details_row');

        $auditoria=Auditoria::find($id);

        return view('details_row.detalle_auditoria')->with(['auditoria' => $auditoria]);

    }

    protected function setupCreateOperation()
    {
        //$this->crud->setValidation(AuditoriaRequest::class);

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
