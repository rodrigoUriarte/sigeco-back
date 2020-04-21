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
        //$inscripciones = Inscripcion::all();
        //busco solo las inscripciones que no tengan una asistencia relacionada
        $inscripciones = Inscripcion::doesntHave('asistencia')->get();
        foreach ($inscripciones as $inscripcion) {
            //creo asistencias solo para fechas menor al dia de hoy
            if (Carbon::createFromDate($inscripcion->fecha_inscripcion)->toDateString() <= Carbon::now()->toDateString()) {
                $rand = rand($min = 1, $max = 10);
                if ($rand <= 8) {
                    $asistencia = Asistencia::create([
                        'fecha_asistencia' => Carbon::createFromDate($inscripcion->fecha_inscripcion)->toDateTimeString(),
                        'asistio' => true,
                        'asistencia_fbh' => false,
                        'inscripcion_id' => $inscripcion->id,
                        'comedor_id' => $inscripcion->comedor_id,
                    ]);
                } else {
                    $asistencia = Asistencia::create([
                        'fecha_asistencia' => null,
                        'asistio' => false,
                        'asistencia_fbh' => false,
                        'inscripcion_id' => $inscripcion->id,
                        'comedor_id' => $inscripcion->comedor_id,
                    ]);
                }
            }
        }
    }
}
