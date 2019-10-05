<?php

namespace App\Observers;

use App\Models\Inscripcion;

class InscripcionObserver
{
    /**
     * Handle the inscripcion "creating" event.
     *
     * @param  \App\Inscripcion  $inscripcion
     * @return void
     */
    public function creating(Inscripcion $inscripcion)
    {
        $inscripcion->menu_asignado_id =
            //Al crear una nueva inscripcion se asigna el Menu Asignado correspondiente a dicha fecha de Inscripcion
            $menuid = \App\Models\MenuAsignado::where('user_id', backpack_user()->id)
            ->whereDate('fecha_inicio', '<=', $inscripcion->fecha_inscripcion)
            ->whereDate('fecha_fin', '>=', $inscripcion->fecha_inscripcion)
            ->first()
            ->id;
    }

    /**
     * Handle the inscripcion "updated" event.
     *
     * @param  \App\Inscripcion  $inscripcion
     * @return void
     */
    public function updated(Inscripcion $inscripcion)
    {
        //
    }

    /**
     * Handle the inscripcion "deleted" event.
     *
     * @param  \App\Inscripcion  $inscripcion
     * @return void
     */
    public function deleted(Inscripcion $inscripcion)
    {
        //
    }

    /**
     * Handle the inscripcion "restored" event.
     *
     * @param  \App\Inscripcion  $inscripcion
     * @return void
     */
    public function restored(Inscripcion $inscripcion)
    {
        //
    }

    /**
     * Handle the inscripcion "force deleted" event.
     *
     * @param  \App\Inscripcion  $inscripcion
     * @return void
     */
    public function forceDeleted(Inscripcion $inscripcion)
    {
        //
    }
}
