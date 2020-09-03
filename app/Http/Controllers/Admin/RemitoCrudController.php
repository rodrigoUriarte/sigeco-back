<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RemitoRequest;
use App\Models\Insumo;
use App\Models\InsumoRemito;
use App\Models\Lote;
use App\Models\Proveedor;
use App\Models\Remito;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Prologue\Alerts\Facades\Alert;

/**
 * Class RemitoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class RemitoCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {
        update as traitUpdate;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation {
        destroy as traitDestroy;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;


    public function setup()
    {
        $this->crud->setModel('App\Models\Remito');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/remito');
        $this->crud->setEntityNameStrings('remito', 'remitos');

        $this->crud->denyAccess(['create', 'update', 'delete', 'list', 'show']);

        if (backpack_user()->hasPermissionTo('createRemito')) {
            $this->crud->allowAccess('create');
        }
        if (backpack_user()->hasPermissionTo('updateRemito')) {
            $this->crud->allowAccess('update');
        }
        if (backpack_user()->hasPermissionTo('deleteRemito')) {
            $this->crud->allowAccess('delete');
        }
        if (backpack_user()->hasPermissionTo('listRemito')) {
            $this->crud->allowAccess('list');
        }
        if (backpack_user()->hasPermissionTo('showRemito')) {
            $this->crud->allowAccess('show');
        }

        //SI el usuario es un admin muestra solo los platos del comedor del cual es responsable
        if (backpack_user()->hasRole('operativo')) {
            $this->crud->addClause('where', 'comedor_id', '=', backpack_user()->persona->comedor_id);
        }
        $this->crud->setCreateView('remitos.create');
        $this->crud->setUpdateView('remitos.edit');
    }

    protected function setupListOperation()
    {
        $this->crud->hasAccessOrFail('list');
        $this->crud->addColumns(['numero', 'fecha', 'proveedor']);

        $this->crud->setColumnDetails('numero', [
            'name' => "numero_masked", // The db column name
            'label' => "Numero", // Table column heading
            'type' => "text",
        ]);

        $this->crud->setColumnDetails('fecha', [
            'name' => "fecha", // The db column name
            'label' => "Fecha", // Table column heading
            'type' => "date",
        ]);

        $this->crud->setColumnDetails('proveedor', [
            'label' => 'Proveedor',
            'type' => 'select',
            'name' => 'proveedor_id', // the db column for the foreign key
            'entity' => 'proveedor', // the method that defines the relationship in your Model
            'attribute' => 'nombre', // foreign key attribute that is shown to user
            'model' => "App\Models\Proveedor", // foreign key model
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('proveedor', function ($q) use ($column, $searchTerm) {
                    $q->where('nombre', 'like', '%' . $searchTerm . '%');
                });
            },
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->hasAccessOrFail('create');
        $this->crud->setValidation(RemitoRequest::class);
    }

    public function create()
    {
        $this->crud->hasAccessOrFail('create');

        // prepare the fields you need to show
        $insumos = Insumo::where('comedor_id', backpack_user()->persona->comedor_id)->get();
        $proveedores = Proveedor::whereHas('comedores', function (Builder $query) {
            $query->where('comedor_id', backpack_user()->persona->comedor_id);
        })->get();
        $fecha = Carbon::now()->toDateString();
        $comedor = backpack_user()->persona->comedor_id;

        $this->data['insumos'] = $insumos;
        $this->data['proveedores'] = $proveedores;
        $this->data['fecha'] = $fecha;
        $this->data['comedor'] = $comedor;

        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.add') . ' ' . $this->crud->entity_name;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getCreateView(), $this->data);
    }

    public function store() //copie los mismo del vendor pero le cambie lo que se inserta en la DB
    {
        $this->crud->hasAccessOrFail('create');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        $insumos = $request->input('insumo', []);
        $cantidad = $request->input('cantidad', []);
        $fecha_vencimiento = $request->input('fecha_vencimiento', []);

        //valido que no se carguen dos insumos con misma fecha de vencimiento
        $insumosCol = collect();
        for ($i = 0; $i < count($insumos); $i++) {
            $aux = collect();
            $aux->put('insumo_id', $insumos[$i]);
            $aux->put('cantidad', $cantidad[$i]);
            $aux->put('fecha_vencimiento', $fecha_vencimiento[$i]);
            $insumosCol->push($aux);
        }
        $insumosGroups = $insumosCol->groupBy(['insumo_id'], $preserveKeys = true);
        $duplicados = collect();
        foreach ($insumosGroups as $key => $value) {
            $duplicados->push($value->duplicates('fecha_vencimiento'));
        }
        foreach ($duplicados as $key => $value) {
            if ($value->isNotEmpty()) {
                throw ValidationException::withMessages(['insumos[]' =>
                'No se puede cargar el mismo insumo con fecha de vencimiento identica.']);
                return false;
            }
        }

        // creo el remito
        $remito = Remito::create($request->all());

        //adjunto los insumos a ese remito
        for ($i = 0; $i < count($insumos); $i++) {
            $remito->insumos()->attach(
                $insumos[$i],
                [
                    'fecha_vencimiento' => $fecha_vencimiento[$i],
                    'cantidad' => $cantidad[$i]
                ]
            );
        }

        //creo un lote por cada insumo en el remito
        foreach ($remito->insumos as $insumo) {
            $lote = Lote::create([
                'comedor_id' => $insumo->comedor_id,
                'insumo_id' => $insumo->id,
                'insumo_remito_id' => $insumo->pivot->id,
                'fecha_vencimiento' => $insumo->pivot->fecha_vencimiento,
                'cantidad' =>  $insumo->pivot->cantidad,
                'usado' =>  false
            ]);
        }

        $this->data['entry'] = $this->crud->entry = $remito;

        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($remito->getKey());
    }

    public function edit($id)
    {
        $this->crud->hasAccessOrFail('update');
        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;
        $this->crud->setOperationSetting('fields', $this->crud->getUpdateFields());

        $insumos = Insumo::where('comedor_id', backpack_user()->persona->comedor_id)->get();
        $proveedores = Proveedor::whereHas('comedores', function (Builder $query) {
            $query->where('comedor_id', backpack_user()->persona->comedor_id);
        })->get();
        $fecha = Carbon::now()->toDateString();
        $comedor = backpack_user()->persona->comedor_id;
        $insumosAsociados = $this->crud->getEntry($id)->insumos;

        $this->data['insumos'] = $insumos;
        $this->data['proveedores'] = $proveedores;
        $this->data['fecha'] = $fecha;
        $this->data['comedor'] = $comedor;
        $this->data['insumosAsociados'] = $insumosAsociados;


        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.edit') . ' ' . $this->crud->entity_name;

        $this->data['id'] = $id;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getEditView(), $this->data);
    }

    public function update()
    {
        $this->crud->hasAccessOrFail('update');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        $insumosReq = collect($request->insumo);
        $cantidadReq = collect($request->cantidad);
        $fecha_vencimientoReq = collect($request->fecha_vencimiento);

        //armo una coleccion con cada fila de los insumos(insumo, cantidad, f venc)
        $insumosN = collect();
        for ($i = 0; $i < count($insumosReq); $i++) {
            $aux = collect();
            $aux->put('insumo_id', $insumosReq->get($i));
            $aux->put('cantidad', $cantidadReq->get($i));
            $aux->put('fecha_vencimiento', $fecha_vencimientoReq->get($i));
            $insumosN->push($aux);
        }

        //valido que no se carguen dos insumos con misma fecha de vencimiento
        $insumosGroups = $insumosN->groupBy(['insumo_id'], $preserveKeys = true);
        $duplicados = collect();
        foreach ($insumosGroups as $key => $value) {
            $duplicados->push($value->duplicates('fecha_vencimiento'));
        }
        foreach ($duplicados as $key => $value) {
            if ($value->isNotEmpty()) {
                throw ValidationException::withMessages(['insumos[]' =>
                'No se puede cargar el mismo insumo con fecha de vencimiento identica.']);
                return Redirect::back()->withInput($request->input());
            }
        }

        //busco el remito antes de su modificacion
        $remito = Remito::findOrFail($request->get($this->crud->model->getKeyName()));

        //recupero los insumos que tiene el remito
        $insumosV = $remito->insumos;
        foreach ($insumosV as $insumoV) {
            //CONTROLO SI EL LOTE FUE USADO QUE NO SE ELIMINE NI SE MODIFIQUEN SUS DATOS
            if ($insumoV->pivot->lote->usado == true) {
                //ACA VALIDO QUE NO SE ELIMINE UN INSUMO CUYO LOTE YA FUE USADO
                if (!$insumosN->contains(function ($value, $key) use ($insumoV) {
                    return (($value->get('insumo_id') == $insumoV->pivot->insumo_id) and
                        ($value->get('fecha_vencimiento') == $insumoV->pivot->fecha_vencimiento));
                })) {
                    throw ValidationException::withMessages(['insumos[]' => 'El lote asociado a este insumo ya fue usado, no se puede eliminar.']);
                    return false;
                } elseif (
                    //ACA VALIDO QUE NO SE EDITE LA FECHA DE VENCIMIENTO DE UN INSUMO CUYO LOTE YA FUE USADO
                    //ESTA VALIDACION NUNCA SALTARIA YA QUE SALTARIA LA ANTERIOR PORQUE SI MODIFICAMOS EL INSUMO
                    //O LA FECHA DE VENCIMIENTO ES COMO QUE ESTUVIERAMOS ELIMINANDO ESE INSUMO DEL REMITO
                    $insumoV->pivot->fecha_vencimiento !=
                    $insumosN->where('insumo_id', $insumoV->pivot->insumo_id)
                    ->where('fecha_vencimiento', $insumoV->pivot->fecha_vencimiento)
                    ->first()
                    ->get('fecha_vencimiento')
                ) {
                    throw ValidationException::withMessages(['insumos[]' => 'El lote asociado a este insumo ya fue usado, no se puede editar la fecha.']);
                    return false;
                } elseif (
                    //ACA VALIDO QUE NO SE EDITE LA CANTIDAD DE UN INSUMO CUYO LOTE YA FUE USADO
                    $insumoV->pivot->cantidad !=
                    $insumosN->where('insumo_id', $insumoV->pivot->insumo_id)
                    ->where('fecha_vencimiento', $insumoV->pivot->fecha_vencimiento)
                    ->first()
                    ->get('cantidad')
                ) {
                    throw ValidationException::withMessages(['insumos[]' => 'El lote asociado a este insumo ya fue usado, no se puede editar la cantidad.']);
                    return false;
                }
                //SI EL LOTE NO FUE USADO VEO QUE SE HACER CON EL INSUMO, PUEDO ELIMINAR O EDITAR
                //SI EL INSUMO NO ESTA EN EL REQUEST SIGNIFICA QUE SE ELIMINO DEL REMITO
                //SI EL INSUMO ESTA EN EL REQUEST HAY QUE VER SI SE MODIFICO ALGUN DATO Y SI SE MODIFICO HAY QUE CAMBIARLO  
            } else {
                //EDITO
                if ($insumosN->contains(function ($value, $key) use ($insumoV) {
                    return (($value->get('insumo_id') == $insumoV->pivot->insumo_id) and
                        ($value->get('fecha_vencimiento') == $insumoV->pivot->fecha_vencimiento));
                })) {
                    if (
                        $insumoV->pivot->fecha_vencimiento !=
                        $insumosN->where('insumo_id', $insumoV->pivot->insumo_id)
                        ->where('fecha_vencimiento', $insumoV->pivot->fecha_vencimiento)
                        ->first()
                        ->get('fecha_vencimiento')
                    ) {
                        $lote = $insumoV->pivot->lote;
                        $lote->fecha_vencimiento = $insumosN->where('insumo_id', $insumoV->pivot->insumo_id)
                            ->where('fecha_vencimiento', $insumoV->pivot->fecha_vencimiento)
                            ->first()
                            ->get('fecha_vencimiento');
                        $lote->save();
                        $remito->insumos()->where('insumo_id', $insumoV->pivot->insumo_id)
                            ->where('fecha_vencimiento', $insumoV->pivot->fecha_vencimiento)
                            ->update(['fecha_vencimiento' => $insumosN->where('insumo_id', $insumoV->pivot->insumo_id)
                                ->where('fecha_vencimiento', $insumoV->pivot->fecha_vencimiento)
                                ->first()
                                ->get('fecha_vencimiento')]);
                    }
                    if (
                        $insumoV->pivot->cantidad !=
                        $insumosN->where('insumo_id', $insumoV->pivot->insumo_id)
                        ->where('fecha_vencimiento', $insumoV->pivot->fecha_vencimiento)
                        ->first()
                        ->get('cantidad')
                    ) {
                        $lote = $insumoV->pivot->lote;
                        $lote->cantidad = $insumosN->where('insumo_id', $insumoV->pivot->insumo_id)
                            ->where('fecha_vencimiento', $insumoV->pivot->fecha_vencimiento)
                            ->first()
                            ->get('cantidad');
                        $lote->save();
                        $remito->insumos()->where('insumo_id', $insumoV->pivot->insumo_id)
                            ->where('fecha_vencimiento', $insumoV->pivot->fecha_vencimiento)
                            ->update(['cantidad' => $insumosN->where('insumo_id', $insumoV->pivot->insumo_id)
                                ->where('fecha_vencimiento', $insumoV->pivot->fecha_vencimiento)
                                ->first()
                                ->get('cantidad')]);
                    }
                    //ELIMINO
                } else {
                    $insumoV->pivot->lote->delete();
                    InsumoRemito::findOrFail($insumoV->pivot->id)->delete();
                }
            }
        }

        //AGREGO LOS NUEVOS INSUMOS QUE VENGAN DEL insumoReq
        //TENGO EN CUENTA QUE PUEDE VENIR UN INSUMO QUE YA HABIA PERO AHORA SE AGREGO OTRO CON DISTINTA FECHA DE VENC.
        foreach ($insumosN as $insumoN) {
            if (!$insumosV->contains(function ($value, $key) use ($insumoN) {
                return (($value->id == $insumoN->get('insumo_id')) and
                    ($value->pivot->fecha_vencimiento == $insumoN->get('fecha_vencimiento')));
            })) {
                $remito->insumos()->attach(
                    $insumoN->get('insumo_id'),
                    [
                        'fecha_vencimiento' => $insumoN->get('fecha_vencimiento'),
                        'cantidad' => $insumoN->get('cantidad')
                    ]
                );
            }
        }

        //vuelvo a cargar el remito con los nuevos insumos
        $remito = Remito::findOrFail($request->get($this->crud->model->getKeyName()));
        //creo un lote para los insumos nuevos del remito
        foreach ($remito->insumos as $insumo) {
            if (Lote::where('insumo_remito_id', $insumo->pivot->id)->doesntExist()) {
                $lote = Lote::create([
                    'comedor_id' => $insumo->comedor_id,
                    'insumo_id' => $insumo->id,
                    'insumo_remito_id' => $insumo->pivot->id,
                    'fecha_vencimiento' => $insumo->pivot->fecha_vencimiento,
                    'cantidad' =>  $insumo->pivot->cantidad,
                    'usado' =>  false
                ]);
            }
        }

        // actualizo los valores de la cabecera del remito
        Remito::findOrFail($request->get($this->crud->model->getKeyName()))
            ->update([
                'numero' => $request->numero,
                'proveedor_id' => $request->proveedor_id
            ]);

        $this->data['entry'] = $this->crud->entry = $remito;

        //show a success message
        \Alert::success(trans('backpack::crud.update_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($remito->getKey());
    }

    protected function setupUpdateOperation()
    {
        $this->crud->hasAccessOrFail('update');
        $this->crud->setValidation(RemitoRequest::class);
    }

    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        //busco el remito
        $remito = Remito::findOrFail($id);

        //recupero los insumos que tiene el remito
        $insumos = $remito->insumos;
        $flag = false;
        foreach ($insumos as $insumo) {
            //CONTROLO SI EL LOTE FUE USADO QUE NO SE ELIMINE SUS DATOS
            if ($insumo->pivot->lote->usado == true) {
                $flag = true;
            }
        }

        if ($flag == true) {
            Alert::error('Uno de los lotes asociados a los insumos de este remito ya fue usado');
            return Alert::getMessages();
        } else {
            //SI NINGUNO DE LOS LOTES ASOCIADOS A CADA UNO DE LOS INSUMOS FUE USADO
            //ELIMINO TODOS LOS LOTES Y DESPUES ELIMINO EL REMITO
            foreach ($insumos as $insumo) {
                $insumo->pivot->lote->delete();
            }
            $remito->insumos()->detach();
            return $this->crud->delete($id);
        }
    }

    protected function setupShowOperation()
    {
        $this->crud->hasAccessOrFail('show');
        $this->crud->set('show.setFromDb', false);
        $this->setupListOperation();

        $this->crud->addColumns(['insumos']);

        $this->crud->setColumnDetails('insumos', [
            // n-n relationship (with pivot table)
            'label'     => 'Insumos', // Table column heading
            'type'      => 'select_multiple_rowStyle',
            'name'      => 'insumos', // the method that defines the relationship in your Model
            'entity'    => 'insumos', // the method that defines the relationship in your Model
            'attribute' => 'descripcion_cantidad_vencimiento', // foreign key attribute that is shown to user
            'model'     => 'App\Models\Insumo', // foreign key model
        ]);
    }
}
