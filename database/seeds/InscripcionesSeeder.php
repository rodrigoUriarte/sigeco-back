<?php

use App\Models\BandaHoraria;
use App\Models\Inscripcion;
use App\Models\MenuAsignado;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class InscripcionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mas = MenuAsignado::doesntHave('inscripciones')->get();
        foreach ($mas as $ma) {
            $finicio = Carbon::createFromDate($ma->fecha_inicio);
            $diasmes = $finicio->daysInMonth;
            $finscripcion = $finicio;
            $dias_pref = $ma->user->diasPreferencia()->with('diaServicio')
                ->get()->pluck('diaServicio.dia');
            for ($i = 0; $i < $diasmes; $i++) {
                if ($finscripcion->toDateString() <= Carbon::now()->toDateString()) {
                    if ($dias_pref->contains($finscripcion->dayName)) {
                        $rand = rand($min = 1, $max = 10);
                        if ($rand <= 9) {
                            $inscripcion = Inscripcion::create([
                                'fecha_inscripcion' => $finscripcion,
                                'retira' => false,
                                'banda_horaria_id' => BandaHoraria::where('comedor_id', $ma->comedor_id)->inRandomOrder()->first()->id,
                                'user_id' => $ma->user_id,
                                'menu_asignado_id' => $ma->id,
                                'comedor_id' => $ma->comedor_id,
                            ]);
                        }
                    }
                }
                $finscripcion->addDay();
            }
        }
    }
}
