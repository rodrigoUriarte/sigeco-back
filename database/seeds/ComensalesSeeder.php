<?php

use App\Models\BackpackUser;
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
    public function run()
    {

        $personas = factory(App\Models\Persona::class, 50)->create();
        foreach ($personas as $persona) {
            $user = BackpackUser::create([
                'name' => $persona->nombre_usuario,
                'email' => $persona->email,
                'password' => bcrypt($persona->dni),
                'persona_id' => $persona->id,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);

            DB::table('model_has_roles')->insert([
                [
                    'role_id' => '4',
                    'model_type' => 'App\Models\BackpackUser',
                    'model_id' => $user->id,
                ]
            ]);
        }
    }
}
