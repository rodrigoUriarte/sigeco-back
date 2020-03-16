<?php

use App\Models\BackpackUser;
use App\Models\BandaHoraria;
use App\Models\DiaPreferencia;
use App\Models\DiaServicio;
use Illuminate\Database\Seeder;

class DiasPreferenciaSeeder extends Seeder
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
                $dias_servicio = DiaServicio::where('comedor_id', $user->persona->comedor_id)
                    ->get();
                if ($dias_servicio->isNotEmpty()) {
                    foreach ($dias_servicio as $ds) {
                        if ($rand = (bool) random_int(0, 1) == 0) {
                            $dia_preferencia = DiaPreferencia::create([
                                'retira' => (bool) random_int(0, 1),
                                'banda_horaria_id' => BandaHoraria::where('comedor_id', $user->persona->comedor_id)->inRandomOrder()->first()->id,
                                'user_id' => $user->id,
                                'dia_servicio_id' => $ds->id,
                            ]);
                        }
                    }
                }
            }
        }
    }
}
