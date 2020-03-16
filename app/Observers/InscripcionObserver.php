<?php

namespace App\Observers;

use App\Models\Inscripcion;
use Carbon\Carbon as CarbonCarbon;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;
use Prologue\Alerts\Facades\Alert;

class InscripcionObserver
{
    /**
     * Handle the inscripcion "created" event.
     *
     * @param  \App\Inscripcion  $inscripcion
     * @return void
     */
    public function created(Inscripcion $inscripcion)
    {
        //
    }

    /**
     * Handle the inscripcion "updated" event.
     *
     * @param  \App\Inscripcion  $inscripcion
     * @return void
     */
    public function updated(Inscripcion $inscripcion)
    {
        
    }

    /**
     * Handle the inscripcion "deleted" event.
     *
     * @param  \App\Inscripcion  $inscripcion
     * @return void
     */
    public function deleting(Inscripcion $inscripcion)
    {
        // $hoy = Carbon::now();
        // $fi = $inscripcion->fecha_inscripcion;
        // $fi = Carbon::parse($fi);
        // $limins = Carbon::createFromTimeString($inscripcion->comedor->parametro->limite_inscripcion);
        // $aux = $limins->diffInMinutes(Carbon::tomorrow());

        // if ($fi->diffInMinutes($hoy, false) >= -$aux) {
        //     //Alert::info('No se puede eliminar una inscripcion despues de la fecha limite.')->flash();
        //     //redirect()->to('admin/inscripcion');
        //     return false;
        // }
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
