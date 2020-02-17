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
        $mas = MenuAsignado::all();
        foreach ($mas as $ma) {
            $fi = Carbon::create(2020, 3, 1);
            for ($i = 0; $i < 31; $i++) {
                if ($fi->isWeekday()) {
                    $inscripcion = Inscripcion::create([
                        'fecha_inscripcion' => $fi,
                        'retira' => false,
                        'banda_horaria_id' => BandaHoraria::where('comedor_id', $ma->comedor_id)->inRandomOrder()->first()->id,
                        'user_id' => $ma->user_id,
                        'menu_asignado_id' => $ma->id,
                        'comedor_id' => $ma->comedor_id,
                    ]);
                }
                $fi->addDay();
            }
        }
    }
}
