<?php

namespace App\Observers;

use App\Models\BandaHoraria;
use Carbon\Carbon;

class BandaHorariaObserver
{
    /**
     * Handle the banda horaria "created" event.
     *
     * @param  \App\BandaHoraria  $bandaHoraria
     * @return void
     */
    public function creating(BandaHoraria $bandaHoraria)
    {
        $hi = Carbon::parse($bandaHoraria->hora_inicio)->format('H:i:s');
        $hf = Carbon::parse($bandaHoraria->hora_fin)->format('H:i:s');
        $bandaHoraria->descripcion = $hi . ' - ' . $hf;
    }

    /**
     * Handle the banda horaria "updated" event.
     *
     * @param  \App\BandaHoraria  $bandaHoraria
     * @return void
     */
    public function updating(BandaHoraria $bandaHoraria)
    {
        $hi = Carbon::parse($bandaHoraria->hora_inicio)->format('H:i:s');
        $hf = Carbon::parse($bandaHoraria->hora_fin)->format('H:i:s');
        $bandaHoraria->descripcion = $hi . ' - ' . $hf;
    }

    /**
     * Handle the banda horaria "deleted" event.
     *
     * @param  \App\BandaHoraria  $bandaHoraria
     * @return void
     */
    public function deleted(BandaHoraria $bandaHoraria)
    {
        //
    }

    /**
     * Handle the banda horaria "restored" event.
     *
     * @param  \App\BandaHoraria  $bandaHoraria
     * @return void
     */
    public function restored(BandaHoraria $bandaHoraria)
    {
        //
    }

    /**
     * Handle the banda horaria "force deleted" event.
     *
     * @param  \App\BandaHoraria  $bandaHoraria
     * @return void
     */
    public function forceDeleted(BandaHoraria $bandaHoraria)
    {
        //
    }
}
