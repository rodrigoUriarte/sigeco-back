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
        if ($asistencia->asistio == false) {
            if ($asistencia->asistencia_fbh == true) {
                $fecha = $asistencia->inscripcion->fecha_inscripcion;
                $hora = $asistencia->inscripcion->bandaHoraria->hora_inicio;
                $combinedDT = date('Y-m-d H:i:s', strtotime("$fecha $hora"));
                $asistencia->fecha_asistencia = $combinedDT;
            } else {
                $asistencia->fecha_asistencia = null;
            }
        }
    }

    /**
     * Handle the asistencia "deleted" event.
     *
     * @param  \App\Asistencia  $asistencia
     * @return void
     */
    public function deleting(Asistencia $asistencia)
    {
        $hoy = Carbon::now();

        $ubh = $asistencia->comedor->bandasHorarias->sortByDesc('hora_fin')->first();
        $hora_fin = Carbon::parse($ubh->hora_fin)->format('H:i');

        $fi = $asistencia->inscripcion->fecha_inscripcion;
        $fi = Carbon::parse($fi);
        $lim = Carbon::createFromTimeString($hora_fin);

        if ($hoy->toDateString() > $fi->toDateString()) {
            //Alert::info('No se puede eliminar una inscripcion despues de la fecha limite.')->flash();
            //redirect()->to('admin/inscripcion');
            return false;
        }
        if ($hoy > $lim) {
            //Alert::info('No se puede eliminar una inscripcion despues de la fecha limite.')->flash();
            //redirect()->to('admin/inscripcion');
            return false;
        }
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
