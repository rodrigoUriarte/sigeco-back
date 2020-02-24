<?php

namespace App\Observers;

use App\Models\MenuAsignado;
use Carbon\Carbon;
use Prologue\Alerts\Facades\Alert;

class MenuAsignadoObserver
{
    /**
     * Handle the menu asignado "created" event.
     *
     * @param  \App\MenuAsignado  $menuAsignado
     * @return void
     */
    public function created(MenuAsignado $menuAsignado)
    {
        //
    }

    /**
     * Handle the menu asignado "updated" event.
     *
     * @param  \App\MenuAsignado  $menuAsignado
     * @return void
     */
    public function updated(MenuAsignado $menuAsignado)
    {
        //
    }

    /**
     * Handle the menu asignado "deleted" event.
     *
     * @param  \App\MenuAsignado  $menuAsignado
     * @return void
     */
    public function deleting(MenuAsignado $menuAsignado)
    {
        $hoy = Carbon::now();
        $fi = $menuAsignado->fecha_inicio;
        $fi = Carbon::parse($fi);
        $fl= $fi->subMonth()->addDays($menuAsignado->comedor->parametro->limite_menu_asignado);
        if ($hoy > $fl) {
            // Alert::info('No se puede eliminar un menu asignado despues de la fecha limite.')->flash();
            // redirect()->to('admin/inscripcion');
            return false;
        } 
    }

    /**
     * Handle the menu asignado "restored" event.
     *
     * @param  \App\MenuAsignado  $menuAsignado
     * @return void
     */
    public function restored(MenuAsignado $menuAsignado)
    {
        //
    }

    /**
     * Handle the menu asignado "force deleted" event.
     *
     * @param  \App\MenuAsignado  $menuAsignado
     * @return void
     */
    public function forceDeleted(MenuAsignado $menuAsignado)
    {
        //
    }
}
