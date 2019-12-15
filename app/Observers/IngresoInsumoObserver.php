<?php

namespace App\Observers;

use App\Models\IngresoInsumo;
use App\Models\Lote;
use Illuminate\Validation\ValidationException;


class IngresoInsumoObserver
{
    /**
     * Handle the ingreso insumo "created" event.
     *
     * @param  \App\IngresoInsumo  $ingresoInsumo
     * @return void
     */
    public function created(IngresoInsumo $ingresoInsumo)
    {

        $lote = Lote::create([
            'comedor_id' => $ingresoInsumo->comedor_id,
            'insumo_id' => $ingresoInsumo->insumo_id,
            'ingreso_insumo_id' => $ingresoInsumo->id,
            'fecha_vencimiento' => $ingresoInsumo->fecha_vencimiento,
            'cantidad' =>  $ingresoInsumo->cantidad,
            'usado' =>  false
        ]);

    }

    /**
     * Handle the ingreso insumo "updated" event.
     *
     * @param  \App\IngresoInsumo  $ingresoInsumo
     * @return void
     */
    public function updating(IngresoInsumo $ingresoInsumo)
    {

        $lote = $ingresoInsumo->lote;

        if ($lote->usado == true) {
            throw ValidationException::withMessages(['details' => 'El lote asociado a este ingreso ya fue usado, no se puede editar.']);
            return false;
        } else {
            $lote->fill([
            'comedor_id' => $ingresoInsumo->comedor_id,
            'insumo_id' => $ingresoInsumo->insumo_id,
            'ingreso_insumo_id' => $ingresoInsumo->id,
            'fecha_vencimiento' => $ingresoInsumo->fecha_vencimiento,
            'cantidad' =>  $ingresoInsumo->cantidad,
            'usado' =>  false
            ]);
            $lote->save();
        }
    }

    /**
     * Handle the ingreso insumo "deleted" event.
     *
     * @param  \App\IngresoInsumo  $ingresoInsumo
     * @return void
     */
    public function deleting(IngresoInsumo $ingresoInsumo)
    {
        // $lote = Lote::where('ingreso_insumo_id',$ingresoInsumo->id);
        $lote = $ingresoInsumo->lote;

        if ($lote->usado == true) {
            // throw ValidationException::withMessages(['details' => 'El lote asociado a este ingreso ya fue usado, no se puede eliminar.']);
            return false;
        } else {
            $ingresoInsumo->lote->delete();     
        }
    }

    /**
     * Handle the ingreso insumo "restored" event.
     *
     * @param  \App\IngresoInsumo  $ingresoInsumo
     * @return void
     */
    public function restored(IngresoInsumo $ingresoInsumo)
    {
        //
    }

    /**
     * Handle the ingreso insumo "force deleted" event.
     *
     * @param  \App\IngresoInsumo  $ingresoInsumo
     * @return void
     */
    public function forceDeleted(IngresoInsumo $ingresoInsumo)
    {
        //
    }
}
