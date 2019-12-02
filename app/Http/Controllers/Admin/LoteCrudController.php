<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LoteRequest;
use App\Models\Lote;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class LoteCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class LoteCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->denyAccess(['delete', 'create', 'update']);

        $this->crud->setListView('personalizadas.vistaLote', $this->data);

        $this->crud->setModel('App\Models\Lote');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/lote');
        $this->crud->setEntityNameStrings('lote', 'lotes');

        //SI el usuario es un admin muestra solo los lotes del comedor del cual es responsable
        if (backpack_user()->hasRole('admin')) {
            $this->crud->addClause('where', 'comedor_id', '=', backpack_user()->persona->comedor_id);
        }
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns(['insumo', 'fecha_vencimiento', 'cantidad', 'usado']);

        $this->crud->setColumnDetails('insumo', [
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

        $this->crud->setColumnDetails('usado',[
            'name' => 'usado',
            'label' => 'Usado',
            'type' => 'boolean',
            // optionally override the Yes/No texts
            'options' => [0 => 'NO', 1 => 'SI']
        ]);
    }
    protected function setupCreateOperation()
    {
        //$this->crud->setValidation(LoteRequest::class);

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

    public function reporteLotes()
    {        
        /**
         * toma en cuenta que para ver los mismos 
         * datos debemos hacer la misma consulta
        **/
        $lotes = Lote::all(); 

        $pdf = \PDF::loadView('reportes.reporteLotes', compact('lotes'));

        return $pdf->stream('listado.pdf');
    }
}
