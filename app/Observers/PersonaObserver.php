<?php

namespace App\Observers;

use App\Models\BackpackUser;
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
        $user = BackpackUser::create([
            'name' => $persona->nombre_usuario,
            'email' => $persona->email,
            'password' => bcrypt($persona->dni),
            'persona_id' => $persona->id,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        
        if (backpack_user()->hasRole('operativo')){

            DB::table('model_has_roles')->insert([
                [
                    'role_id' => '4',
                    'model_type' => 'App\Models\BackpackUser',
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
