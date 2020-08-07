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
                    'fecha_inicio' => '2020-02-01',
                    'fecha_fin' => '2020-02-29',
                    'user_id' => $user->id,
                    'menu_id' => Menu::where('comedor_id', $user->persona->comedor->id)->inRandomOrder()->first()->id,
                    'comedor_id' => $user->persona->comedor->id,
                ]);
                $ma = MenuAsignado::create([
                    'fecha_inicio' => '2020-03-01',
                    'fecha_fin' => '2020-03-31',
                    'user_id' => $user->id,
                    'menu_id' => Menu::where('comedor_id', $user->persona->comedor->id)->inRandomOrder()->first()->id,
                    'comedor_id' => $user->persona->comedor->id,
                ]);
                $ma = MenuAsignado::create([
                    'fecha_inicio' => '2020-04-01',
                    'fecha_fin' => '2020-04-30',
                    'user_id' => $user->id,
                    'menu_id' => Menu::where('comedor_id', $user->persona->comedor->id)->inRandomOrder()->first()->id,
                    'comedor_id' => $user->persona->comedor->id,
                ]);
                $ma = MenuAsignado::create([
                    'fecha_inicio' => '2020-04-01',
                    'fecha_fin' => '2020-04-30',
                    'user_id' => $user->id,
                    'menu_id' => Menu::where('comedor_id', $user->persona->comedor->id)->inRandomOrder()->first()->id,
                    'comedor_id' => $user->persona->comedor->id,
                ]);
                $ma = MenuAsignado::create([
                    'fecha_inicio' => '2020-05-01',
                    'fecha_fin' => '2020-05-31',
                    'user_id' => $user->id,
                    'menu_id' => Menu::where('comedor_id', $user->persona->comedor->id)->inRandomOrder()->first()->id,
                    'comedor_id' => $user->persona->comedor->id,
                ]);
                $ma = MenuAsignado::create([
                    'fecha_inicio' => '2020-06-01',
                    'fecha_fin' => '2020-06-30',
                    'user_id' => $user->id,
                    'menu_id' => Menu::where('comedor_id', $user->persona->comedor->id)->inRandomOrder()->first()->id,
                    'comedor_id' => $user->persona->comedor->id, 
                ]);
                $ma = MenuAsignado::create([
                    'fecha_inicio' => '2020-07-01',
                    'fecha_fin' => '2020-07-31',
                    'user_id' => $user->id,
                    'menu_id' => Menu::where('comedor_id', $user->persona->comedor->id)->inRandomOrder()->first()->id,
                    'comedor_id' => $user->persona->comedor->id,
                ]);
                $ma = MenuAsignado::create([
                    'fecha_inicio' => '2020-08-01',
                    'fecha_fin' => '2020-08-31',
                    'user_id' => $user->id,
                    'menu_id' => Menu::where('comedor_id', $user->persona->comedor->id)->inRandomOrder()->first()->id,
                    'comedor_id' => $user->persona->comedor->id,
                ]);
            }
        }
    }
}
