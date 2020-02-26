<?php

use App\Models\BackpackUser;
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
        $users = BackpackUser::all();
        foreach ($users as $user) {
            if ($user->hasRole('comensal')) {
                $ma = MenuAsignado::create([
                    'fecha_inicio' => '2020-02-01',
                    'fecha_fin' => '2020-02-29',
                    'user_id' => $user->id,
                    'menu_id' => Menu::where('comedor_id', $user->persona->comedor->id)->inRandomOrder()->first()->id,
                    'comedor_id' => $user->persona->comedor->id,
                    'created_at' => Carbon::createFromDate(2020,01,01),
                    'updated_at' => Carbon::createFromDate(2020,01,01)
                ]);
            }
        }
    }
}
