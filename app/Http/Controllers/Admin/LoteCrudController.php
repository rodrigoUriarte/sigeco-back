<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LoteRequest;
use App\Models\Insumo;
use App\Models\Lote;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


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


        $this->crud->setModel('App\Models\Lote');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/lote');
        $this->crud->setEntityNameStrings('lote', 'lotes');

        //SI el usuario es un admin muestra solo los lotes del comedor del cual es responsable
        if (backpack_user()->hasRole('admin')) {
            $this->crud->setListView('personalizadas.vistaLote', $this->data);
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

    public function reporteLotes(Request $request)
    {        
        $lotes = Lote::all(); 

        $filtro_insumo = $request->filtro_insumo;
        $filtro_fecha_vencimiento = $request->filtro_fecha_vencimiento;

        $insumo = null;
        $fecha_vencimiento = null;

        if ($request->filtro_insumo != null) { //aca pregunto si el filtro que viene en el request esta vacio y sino hago el filtro y asi por cada if
            $insumo = Insumo::where('descripcion',$filtro_insumo);
            foreach ($lotes as $id => $lote) {
                if ($lote->insumo->descripcion != $filtro_insumo) {
                    $lotes->pull($id);
                }
            }
        }

        if ($request->filtro_fecha_vencimiento != null) { //aca pregunto si el filtro que viene en el request esta vacio y sino hago el filtro y asi por cada if
            $lotex = Lote::where('fecha_vencimiento',$filtro_fecha_vencimiento);
            foreach ($lotes as $id => $lote) {
                if ($lote->fecha_vencimiento > $filtro_fecha_vencimiento) {
                    $lotes->pull($id);
                }
            }
        }

        $pdf = \PDF::loadView('reportes.reporteLotes', 
        compact('lotes','insumo','filtro_insumo','fecha_vencimiento','filtro_fecha_vencimiento'));

        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $y = $canvas->get_height() - 20;
        $pdf->getDomPDF()->get_canvas()->page_text(500, $y, "Pagina {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));

        $nombre = 'Reporte-Lotes-' . Carbon::now()->format('d/m/Y G:i') . '.pdf';
        return $pdf->stream($nombre);
    }
}
