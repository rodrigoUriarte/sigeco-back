<?php

namespace App\Observers;

use App\User;
use App\Models\Persona;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PersonaObserver
{
    /**
     * Handle the persona "created" event.
     *
     * @param  \App\Persona  $persona
     * @return void
     */
    public function created(Persona $persona)
    {
        $user = User::create([
            'name' => $persona->nombre_usuario,
            'email' => $persona->email,
            'password' => bcrypt($persona->dni),
            'persona_id' => $persona->id,
        ]);

        
        if (backpack_user()->hasRole('operativo')){

            DB::table('model_has_roles')->insert([
                [
                    'role_id' => '4',
                    'model_type' => 'App\User',
                    'model_id' => $user->id,
                ]
            ]);
        }

    }

    /**
     * Handle the persona "updated" event.
     *
     * @param  \App\Persona  $persona
     * @return void
     */
    public function updated(Persona $persona)
    {
        $this->deleting($persona);
        $this->created($persona);
    }

    /**
     * Handle the persona "deleted" event.
     *
     * @param  \App\Persona  $persona
     * @return void
     */
    public function deleting(Persona $persona)
    {
        $user = $persona->user;
        $user->delete();
    }

    /**
     * Handle the persona "restored" event.
     *
     * @param  \App\Persona  $persona
     * @return void
     */
    public function restored(Persona $persona)
    {
        //
    }

    /**
     * Handle the persona "force deleted" event.
     *
     * @param  \App\Persona  $persona
     * @return void
     */
    public function forceDeleted(Persona $persona)
    {
        //
    }
}
