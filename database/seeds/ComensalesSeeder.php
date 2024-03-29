<?php

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComensalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function factoryWithoutObservers($class, $name = 'default')
    {
        $class::flushEventListeners();
        return factory($class, $name);
    }
    public function run()
    {
        $personas = $this->factoryWithoutObservers(App\Models\Persona::class, 25)->create();
        foreach ($personas as $persona) {
            $user = new User;
            $user->fill([
                'name' => $persona->nombre_usuario,
                'email' => $persona->email,
                'password' => bcrypt($persona->dni),
                'persona_id' => $persona->id,
            ]);
            $user->save();

            DB::table('model_has_roles')->insert([
                [
                    'role_id' => '4',
                    'model_type' => 'App\User',
                    'model_id' => $user->id,
                ]
            ]);
        }
    }
}
