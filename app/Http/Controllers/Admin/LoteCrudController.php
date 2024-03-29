<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LoteRequest;
use App\Models\Insumo;
use App\Models\Lote;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Redirect;
use Prologue\Alerts\Facades\Alert;
use Mpdf\Mpdf;

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


        $this->crud->setModel('App\Models\Lote');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/lote');
        $this->crud->setEntityNameStrings('lote', 'lotes');

        $this->crud->denyAccess(['create', 'update', 'delete', 'list', 'show']);

        if (backpack_user()->hasPermissionTo('createLote')) {
            $this->crud->allowAccess('create');
        }
        if (backpack_user()->hasPermissionTo('updateLote')) {
            $this->crud->allowAccess('update');
        }
        if (backpack_user()->hasPermissionTo('deleteLote')) {
            $this->crud->allowAccess('delete');
        }
        if (backpack_user()->hasPermissionTo('listLote')) {
            $this->crud->allowAccess('list');
        }
        if (backpack_user()->hasPermissionTo('showLote')) {
            $this->crud->allowAccess('show');
        }

        //SI el usuario es un admin muestra solo los lotes del comedor del cual es responsable
        if (backpack_user()->hasRole('operativo')) {
            $insumos = Insumo::where('comedor_id', backpack_user()->persona->comedor_id)->get();
            $this->data['insumos'] = $insumos;
            $this->crud->setListView('personalizadas.vistaLote', $this->data);
            $this->crud->addClause('where', 'comedor_id', '=', backpack_user()->persona->comedor_id);
        }
    }

    protected function setupListOperation()
    {
        $this->crud->hasAccessOrFail('list');
        $this->crud->addColumns(['insumo', 'fecha_vencimiento', 'cantidad', 'unidad_medida', 'usado']);

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
                });
            },
        ]);

        $this->crud->setColumnDetails('fecha_vencimiento', [
            'name' => "fecha_vencimiento", // The db column name
            'label' => "Fecha Vencimiento", // Table column heading
            'type' => "date",
            // 'format' => 'l j F Y', // use something else than the base.default_date_format config value
        ]);


        $this->crud->setColumnDetails('cantidad', [
            'name' => "cantidad", // The db column name
            'label' => "Cantidad", // Table column heading
            'type' => "number",
            'decimals' => 2,
        ]);

        $this->crud->setColumnDetails('unidad_medida', [
            'name' => "insumo.unidad_medida", // The db column name
            'label' => "Unidad Medida", // Table column heading
            'type' => "text",
        ]);

        $this->crud->setColumnDetails('usado', [
            'name' => 'usado',
            'label' => 'Usado',
            'type' => 'boolean',
            // optionally override the Yes/No texts
            'options' => [0 => 'NO', 1 => 'SI']
        ]);

        // daterange filter
        $this->crud->addFilter(
            [
                'type'  => 'date_range',
                'name'  => 'from_to',
                'label' => 'Fecha Vencimiento'
            ],
            false,
            function ($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('where', 'fecha_vencimiento', '>=', $dates->from);
                $this->crud->addClause('where', 'fecha_vencimiento', '<=', $dates->to . ' 23:59:59');
            }
        );
    }
    protected function setupCreateOperation()
    {
        $this->crud->hasAccessOrFail('create');
        //$this->crud->setValidation(LoteRequest::class);

    }

    protected function setupUpdateOperation()
    {
        $this->crud->hasAccessOrFail('update');
        //$this->setupCreateOperation();
    }

    protected function setupShowOperation()
    {
        $this->crud->hasAccessOrFail('show');
        $this->crud->set('show.setFromDb', false);
        $this->setupListOperation();
    }

    public function reporteLotes(Request $request)
    {
        $lotes = Lote::all();

        $filtro_insumo = $request->filtro_insumo;
        $filtro_fecha_vencimiento_desde = $request->filtro_fecha_vencimiento_desde;
        $filtro_fecha_vencimiento_hasta = $request->filtro_fecha_vencimiento_hasta;
        $filtro_lotes_vacios = $request->filtro_lotes_vacios;

        if (($filtro_fecha_vencimiento_desde > $filtro_fecha_vencimiento_hasta) and 
        ($filtro_fecha_vencimiento_desde!=null and $filtro_fecha_vencimiento_hasta!=null)) {
            Alert::info('El dato "fecha desde" no puede ser mayor a "fecha hasta"')->flash();
            return Redirect::to('admin/lote');            
        }

        if ($request->filtro_insumo != null) { //aca pregunto si el filtro que viene en el request esta vacio y sino hago el filtro y asi por cada if
            foreach ($lotes as $id => $lote) {
                if ($lote->insumo->descripcion != $filtro_insumo) {
                    $lotes->pull($id);
                }
            }
        }

        if ($request->filtro_fecha_vencimiento_desde != null) { //aca pregunto si el filtro que viene en el request esta vacio y sino hago el filtro y asi por cada if
            foreach ($lotes as $id => $lote) {
                if ($lote->fecha_vencimiento < $filtro_fecha_vencimiento_desde) {
                    $lotes->pull($id);
                }
            }
            //DESPUES DE USAR EL FILTRO PARA LAS OPERACIONES, PASO EL FILTRO A LA VISTA CON EL FORMATO CORRECTO
            $myDate = Date::createFromFormat('Y-m-d', $filtro_fecha_vencimiento_desde);
            $filtro_fecha_vencimiento_desde = date_format($myDate, 'd-m-Y');
        }

        if ($request->filtro_fecha_vencimiento_hasta != null) { //aca pregunto si el filtro que viene en el request esta vacio y sino hago el filtro y asi por cada if
            foreach ($lotes as $id => $lote) {
                if ($lote->fecha_vencimiento > $filtro_fecha_vencimiento_hasta) {
                    $lotes->pull($id);
                }
            }
            //DESPUES DE USAR EL FILTRO PARA LAS OPERACIONES, PASO EL FILTRO A LA VISTA CON EL FORMATO CORRECTO
            $myDate2 = Date::createFromFormat('Y-m-d', $filtro_fecha_vencimiento_hasta);
            $filtro_fecha_vencimiento_hasta = date_format($myDate2, 'd-m-Y');
        }

        if ($request->filtro_lotes_vacios == null) { //aca pregunto si el filtro que viene en el request esta vacio y sino hago el filtro y asi por cada if
                foreach ($lotes as $id => $lote) {
                    if ($lote->cantidad == 0) {
                        $lotes->pull($id);
                    }
                }
            
        }

        $html = view('reportes.reporteLotes',
        ['lotes'=>$lotes
        ->sortBy(function ($lote, $key) {
            return [
                $lote->insumo->descripcion,
                $lote->fecha_vencimiento,
            ];
        }), 
        'filtro_insumo'=>$filtro_insumo,
        'filtro_fecha_vencimiento_desde'=>$filtro_fecha_vencimiento_desde,
        'filtro_fecha_vencimiento_hasta'=>$filtro_fecha_vencimiento_hasta,
        'filtro_lotes_vacios'=>$filtro_lotes_vacios]);

        $mpdf = new Mpdf([
            'margin_left' => '10', 
            'margin_right' => '10', 
            'margin_top' => '10', 
            'margin_bottom' => '15',
        ]);
        $mpdf->setFooter('{PAGENO} / {nb}');
        $nombre = 'Reporte-Estimacion-Compra-' . Carbon::now()->format('d/m/Y G:i') . '.pdf';
        $mpdf->WriteHTML($html);
        $mpdf->Output($nombre,"I");

    }
}
