<?php

namespace App\Observers;

use App\Models\Lote;
use App\Models\Remito;

class RemitoObserver
{
    /**
     * Handle the remito "created" event.
     *
     * @param  \App\Remito  $remito
     * @return void
     */
    public function created(Remito $remito)
    {
        //
    }

    /**
     * Handle the remito "updated" event.
     *
     * @param  \App\Remito  $remito
     * @return void
     */
    public function updating(Remito $remito)
    {
       //
    }

    /**
     * Handle the remito "deleted" event.
     *
     * @param  \App\Remito  $remito
     * @return void
     */
    public function deleted(Remito $remito)
    {
        //
    }

    /**
     * Handle the remito "restored" event.
     *
     * @param  \App\Remito  $remito
     * @return void
     */
    public function restored(Remito $remito)
    {
        //
    }

    /**
     * Handle the remito "force deleted" event.
     *
     * @param  \App\Remito  $remito
     * @return void
     */
    public function forceDeleted(Remito $remito)
    {
        //
    }
}
