<?php

namespace App\Observers;

use App\Models\Justificacion;

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
        if ($justificacion->asistencia->sancion) {
            $inasistenciasRelacionadas = $justificacion->asistencia->sancion->asistencias()->withCount('justificacion')->get();
            $justificadas=0;
            foreach ($inasistenciasRelacionadas as $iR) {
                if ($iR->justificacion_count == 1) {
                    $justificadas +=1;
                }
            }
            $cfRegla = $justificacion->asistencia->sancion->regla->cantidad_faltas;

            $aux = $inasistenciasRelacionadas->count() - $justificadas;
            if ($inasistenciasRelacionadas->count() - $justificadas < $cfRegla) {
                $justificacion->asistencia->sancion->activa=false;
                $justificacion->asistencia->sancion->save();
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
        $justificacion->asistencia->sancion->activa=true;
        $justificacion->asistencia->sancion->save();
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
