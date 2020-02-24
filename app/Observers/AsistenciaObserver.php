<?php

namespace App\Observers;

use App\Models\Asistencia;
use Carbon\Carbon;

class AsistenciaObserver
{
    /**
     * Handle the asistencia "created" event.
     *
     * @param  \App\Asistencia  $asistencia
     * @return void
     */
    public function created(Asistencia $asistencia)
    {
        //
    }

    /**
     * Handle the asistencia "updated" event.
     *
     * @param  \App\Asistencia  $asistencia
     * @return void
     */
    public function updating(Asistencia $asistencia)
    {
        if ($asistencia->getOriginal('asistio') == false) {
            if ($asistencia->asistio == true) {
                $fecha= $asistencia->inscripcion->fecha_inscripcion;
                $hora = $asistencia->inscripcion->bandaHoraria->hora_inicio;
                $combinedDT = date('Y-m-d H:i:s', strtotime("$fecha $hora"));
                $asistencia->fecha_asistencia = $combinedDT;
            } else {
                $asistencia->fecha_asistencia = null;
            }
        } else {
            if ($asistencia->asistio == false) {
                $asistencia->fecha_asistencia = null;
            } else {
                //$asistencia->fecha_asistencia = null;
            }
        }
    }

    /**
     * Handle the asistencia "deleted" event.
     *
     * @param  \App\Asistencia  $asistencia
     * @return void
     */
    public function deleted(Asistencia $asistencia)
    {
        //
    }

    /**
     * Handle the asistencia "restored" event.
     *
     * @param  \App\Asistencia  $asistencia
     * @return void
     */
    public function restored(Asistencia $asistencia)
    {
        //
    }

    /**
     * Handle the asistencia "force deleted" event.
     *
     * @param  \App\Asistencia  $asistencia
     * @return void
     */
    public function forceDeleted(Asistencia $asistencia)
    {
        //
    }
}
