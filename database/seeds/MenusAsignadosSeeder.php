<?php

use App\User;
use App\Models\Menu;
use App\Models\MenuAsignado;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MenusAsignadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        foreach ($users as $user) {
            if ($user->hasRole('comensal')) {
                $ma = MenuAsignado::create([
                    'fecha_inicio' => '2021-01-01',
                    'fecha_fin' => '2021-01-31',
                    'user_id' => $user->id,
                    'menu_id' => Menu::where('comedor_id', $user->persona->comedor->id)->inRandomOrder()->first()->id,
                    'comedor_id' => $user->persona->comedor->id,
                    'created_at' => '2020-12-10 05:30:30'
                ]);
                $ma = MenuAsignado::create([
                    'fecha_inicio' => '2021-02-01',
                    'fecha_fin' => '2021-02-28',
                    'user_id' => $user->id,
                    'menu_id' => Menu::where('comedor_id', $user->persona->comedor->id)->inRandomOrder()->first()->id,
                    'comedor_id' => $user->persona->comedor->id,
                    'created_at' => '2021-01-10 05:30:30'
                ]);
            }
        }
    }
}
