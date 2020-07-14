<?php

namespace App\Observers;

use App\Models\Proveedor;

class ProveedorObserver
{
    /**
     * Handle the proveedor "created" event.
     *
     * @param  \App\Proveedor  $proveedor
     * @return void
     */
    public function creating(Proveedor $proveedor)
    {
        //
    }

    /**
     * Handle the proveedor "updated" event.
     *
     * @param  \App\Proveedor  $proveedor
     * @return void
     */
    public function updating(Proveedor $proveedor)
    {
        //
    }

    /**
     * Handle the proveedor "deleted" event.
     *
     * @param  \App\Proveedor  $proveedor
     * @return void
     */

    public function deleting(Proveedor $proveedor)
    {
        $tieneRemitos = $proveedor->remitos()->exists();
        if ($tieneRemitos == true) {
            return false;
        } else {
            $proveedor->comedores()->detach();
        }
    }

    /**
     * Handle the proveedor "restored" event.
     *
     * @param  \App\Proveedor  $proveedor
     * @return void
     */
    public function restored(Proveedor $proveedor)
    {
        //
    }

    /**
     * Handle the proveedor "force deleted" event.
     *
     * @param  \App\Proveedor  $proveedor
     * @return void
     */
    public function forceDeleted(Proveedor $proveedor)
    {
        //
    }
}
