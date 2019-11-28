<?php

namespace App\Observers;

use App\Models\Lote;
use App\Models\PlatoAsignado;
use App\Models\Inscripcion;
use Illuminate\Support\Facades\DB;



class PlatoAsignadoObserver
{
    /**
     * Handle the plato asignado "created" event.
     *
     * @param  \App\PlatoAsignado  $platoAsignado
     * @return void
     */
    public function created(PlatoAsignado $platoAsignado)
    {
        $fecha = $platoAsignado->fecha;
        $comedor = $platoAsignado->comedor_id;
        $menu = $platoAsignado->menu_id;

        $plato = $platoAsignado->plato;
        $insumos_plato = $plato->insumosPlatos;

        $cantidad_inscripciones = Inscripcion::where('fecha_inscripcion', $fecha)
            ->where('comedor_id', $comedor)
            ->whereHas('menuAsignado',  function ($q) use ($menu) {
                $q->where('menu_id', $menu);
            })
            ->count();

        foreach ($insumos_plato as $insumo_plato) {
            $cn_insumo = ($insumo_plato->cantidad * $cantidad_inscripciones);
            $lotes = Lote::where('comedor_id', $insumo_plato->comedor_id)
                ->where('insumo_id', $insumo_plato->insumo_id)
                ->orderBy('fecha_vencimiento', 'asc')
                ->get();

            $aux = $cn_insumo;
            foreach ($lotes as $lote) {
                if ($aux > 0) {
                    if ($lote->cantidad < $aux) {
                        DB::table('lote_plato_asignado')->insert([
                            'plato_asignado_id' => $platoAsignado->id,
                            'lote_id' => $lote->id,
                            'comedor_id' =>backpack_user()->persona->comedor_id,
                            'cantidad' => $lote->cantidad,
                        ]);
                        $aux -= $lote->cantidad;
                        //$lote->cantidad = 0;
                        $lote->cantidad -= $lote->cantidad;
                        $lote->usado = true;
                    } else {
                        DB::table('lote_plato_asignado')->insert([
                            'plato_asignado_id' => $platoAsignado->id,
                            'lote_id' => $lote->id,
                            'comedor_id' =>backpack_user()->persona->comedor_id,
                            'cantidad' => $aux,
                        ]);
                        $lote->cantidad -= $aux;
                        $lote->usado = true;
                        $aux -= $aux;

                    }
                }
                $lote->save();
            }
        }
    }

    /**
     * Handle the plato asignado "updated" event.
     *
     * @param  \App\PlatoAsignado  $platoAsignado
     * @return void
     */
    public function updated(PlatoAsignado $platoAsignado)
    {
        $this->deleting($platoAsignado);
        $this->created($platoAsignado);
    }

    /**
     * Handle the plato asignado "deleted" event.
     *
     * @param  \App\PlatoAsignado  $platoAsignado
     * @return void
     */
    public function deleting(PlatoAsignado $platoAsignado)
    {

        $lotes = $platoAsignado->lotes;

        foreach ($lotes as $lote) {
            $cdl = $lote->pivot->cantidad;
            $lote->cantidad += $cdl;
            if ($lote->ingresoInsumo->cantidad = $cdl) {
                $lote->usado = false;
            }
            $lote->save();
        }

        $lpa = DB::table('lote_plato_asignado')->where('plato_asignado_id', '=', $platoAsignado->id)->delete();

    }

    /**
     * Handle the plato asignado "restored" event.
     *
     * @param  \App\PlatoAsignado  $platoAsignado
     * @return void
     */
    public function restored(PlatoAsignado $platoAsignado)
    {
        //
    }

    /**
     * Handle the plato asignado "force deleted" event.
     *
     * @param  \App\PlatoAsignado  $platoAsignado
     * @return void
     */
    public function forceDeleted(PlatoAsignado $platoAsignado)
    {
        //
    }
}
