<?php

namespace App\Observers;

use App\Models\Parametro;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class ParametroObserver
{
    /**
     * Handle the parametro "created" event.
     *
     * @param  \App\Parametro  $parametro
     * @return void
     */
    public function creating(Parametro $parametro)
    {
        //
    }

    /**
     * Handle the parametro "updated" event.
     *
     * @param  \App\Parametro  $parametro
     * @return void
     */
    public function updating(Parametro $parametro)
    {
        $parametroOriginal = new Parametro($parametro->getOriginal());

        $liN = Carbon::parse($parametro->limite_inscripcion)->format('H:i');
        $liO = Carbon::parse($parametroOriginal->limite_inscripcion)->format('H:i');
        $hora = Carbon::now()->format("H:i");

        $lmaN = $parametro->limite_menu_asignado;
        $lmaO = $parametroOriginal->limite_menu_asignado;
        $dia = Carbon::now()->format('j');

        if ($liN > $liO) {
            if (($hora >= $liO) and ($hora <= $liN)) {
                $error = ValidationException::withMessages(['limite_inscripcion' =>
                'Puede hacer un cambio de horario posterior para el limite de inscripcion,
                 en el rango de ' . $liN . ' a ' . $liO]);
                throw $error;
            }
        }
        if ($liN < $liO) {
            if (($hora <= $liO) and ($hora >= $liN)) {
                $error = ValidationException::withMessages(['limite_inscripcion' =>
                'Puede hacer un cambio de horario anterior para el limite de inscripcion,
                 en el rango de ' . $liO . ' a ' . $liN]);
                 throw $error;
                }
        }

        if ($lmaN > $lmaO) {
            if (($dia >= $lmaO) and ($dia <= $lmaN)) {
                $error = ValidationException::withMessages(['limite_menu_asignado' =>
                'Puede hacer un cambio de fecha posterior para el limite de menus asignados,
                 en el rango de fechas ' . $lmaN . ' a ' . $lmaO.' del mes']);
                throw $error;
            }
        }
        if ($lmaN < $lmaO) {
            if (($dia <= $lmaO) and ($dia >= $lmaN)) {
                $error = ValidationException::withMessages(['limite_menu_asignado' =>
                'Puede hacer un cambio de fecha anterior para el limite de menus asignados,
                 en el rango de fechas ' . $lmaO . ' a ' . $lmaN.' del mes']);
                 throw $error;
                }
        }

    }

    /**
     * Handle the parametro "deleted" event.
     *
     * @param  \App\Parametro  $parametro
     * @return void
     */
    public function deleted(Parametro $parametro)
    {
        //
    }

    /**
     * Handle the parametro "restored" event.
     *
     * @param  \App\Parametro  $parametro
     * @return void
     */
    public function restored(Parametro $parametro)
    {
        //
    }

    /**
     * Handle the parametro "force deleted" event.
     *
     * @param  \App\Parametro  $parametro
     * @return void
     */
    public function forceDeleted(Parametro $parametro)
    {
        //
    }
}
