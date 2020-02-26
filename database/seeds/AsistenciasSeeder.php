<?php

use App\Models\Asistencia;
use App\Models\Inscripcion;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AsistenciasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $inscripciones = Inscripcion::all();
        foreach ($inscripciones as $inscripcion) {
            if (Carbon::createFromDate($inscripcion->fecha_inscripcion)->toDateString() <= Carbon::now()->toDateString()) {
                $rand = rand($min = 2, $max = 4);
                if ($rand % 2 == 0) {
                    $asistencia = Asistencia::create([
                        'fecha_asistencia' => Carbon::createFromDate($inscripcion->fecha_inscripcion)->toDateTimeString(),
                        'asistio' => true,
                        'inscripcion_id' => $inscripcion->id,
                        'comedor_id' => $inscripcion->comedor_id,
                    ]);
                } else {
                    $asistencia = Asistencia::create([
                        'fecha_asistencia' => null,
                        'asistio' => false,
                        'inscripcion_id' => $inscripcion->id,
                        'comedor_id' => $inscripcion->comedor_id,
                    ]);
                }
            }
        }
    }
}
