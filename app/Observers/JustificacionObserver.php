<?php

namespace App\Observers;

use App\Models\Asistencia;
use App\Models\Justificacion;
use App\Models\Sancion;

class JustificacionObserver
{
    /**
     * Handle the justificacion "created" event.
     *
     * @param  \App\Justificacion  $justificacion
     * @return void
     */
    public function created(Justificacion $justificacion)
    {
        $sancionesAsociadas = $justificacion->asistencia->sanciones;
        if ($sancionesAsociadas->isNotEmpty()) {
            foreach ($sancionesAsociadas as $sA) {
                $sA->activa = false;
                $sA->save();
            }
        }
    }

    /**
     * Handle the justificacion "updated" event.
     *
     * @param  \App\Justificacion  $justificacion
     * @return void
     */
    public function updated(Justificacion $justificacion)
    {
        $justificacionOriginal = new Justificacion($justificacion->getOriginal());
        $this->deleted($justificacionOriginal);
        $this->created($justificacion);
    }

    /**
     * Handle the justificacion "deleted" event.
     *
     * @param  \App\Justificacion  $justificacion
     * @return void
     */
    public function deleted(Justificacion $justificacion)
    {
        $sancionesAsociadas = $justificacion->asistencia->sanciones;
        if ($sancionesAsociadas->isNotEmpty()) {
            foreach ($sancionesAsociadas as $sA) {
                $sA->activa = true;
                $sA->save();
            }
        }
    }

    /**
     * Handle the justificacion "restored" event.
     *
     * @param  \App\Justificacion  $justificacion
     * @return void
     */
    public function restored(Justificacion $justificacion)
    {
        //
    }

    /**
     * Handle the justificacion "force deleted" event.
     *
     * @param  \App\Justificacion  $justificacion
     * @return void
     */
    public function forceDeleted(Justificacion $justificacion)
    {
        //
    }
}
