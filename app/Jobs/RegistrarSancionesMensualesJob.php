<?php

namespace App\Jobs;

use App\Models\Asistencia;
use App\User;
use App\Models\DiaServicio;
use App\Models\Regla;
use App\Models\Sancion;
use App\Notifications\AvisoWppSancion;
use DiasPreferenciaSeeder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RegistrarSancionesMensualesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $comedor_id;

    public function __construct($comedor_id)
    {
        $this->comedor_id = $comedor_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $reglasM = Regla::where('comedor_id', $this->comedor_id)
            ->where('tiempo', "mes")
            ->get();

        if (!$reglasM->isEmpty()) {

            $users = User::whereHas('persona', function ($query) {
                $query
                    ->where('comedor_id', $this->comedor_id);
            })->get();

            foreach ($reglasM as $reglaM) {
                foreach ($users as $user) {
                    //coleccion de todas las inasistencias que no tienen una sancion asociada, de un determinado usuario de la ultima semana
                    $inasistencias = Asistencia::where('comedor_id', $this->comedor_id)
                        ->doesntHave('sanciones')
                        ->doesntHave('justificacion')
                        ->where('asistio', false)
                        ->where('asistencia_fbh', false)
                        ->whereHas('inscripcion', function ($query) use ($user) {
                            $query
                                ->where('user_id', $user->id)
                                ->where('fecha_inscripcion', '>=', Carbon::now()->subMonth()->toDateString())
                                ->where('fecha_inscripcion', '<=', Carbon::now()->subDay()->toDateString());
                        })
                        ->get();

                    $inasistencias = $inasistencias
                        ->sortBy(function ($query) {
                            return $query->inscripcion->fecha_inscripcion;
                        });

                    if ($inasistencias->count() >= $reglaM->cantidad_faltas) {
                        //no son dias corridos tengo que cambiar desde hasta por una fecha concreta

                        $dias_servicio = DiaServicio::where('comedor_id', $this->comedor_id)->get()->pluck('dia');
                        $dia_sancion = Carbon::now()->addDay();
                        for ($i = 0; $i < $reglaM->dias_sancion; $i++) {
                            while (
                                (!$dias_servicio->contains($dia_sancion->dayName)) or
                                ($tieneSancionActivaFecha = $user->sanciones->where('fecha', $dia_sancion->toDateString())
                                    ->where('activa', 1)
                                    ->isNotEmpty())
                            ) {
                                $dia_sancion->addDay();
                            }

                            $sancion = Sancion::create(
                                [
                                    'fecha' => $dia_sancion->toDateString(),
                                    'activa' => 1,
                                    'user_id' => $user->id,
                                    'comedor_id' => $user->persona->comedor_id,
                                    'regla_id' => $reglaM->id,
                                ]
                            );
                            foreach($inasistencias->slice(0,$reglaM->cantidad_faltas) as $inasistencia) {
                                DB::table('asistencia_sancion')->insert([
                                    [
                                        'asistencia_id' => $inasistencia->id,
                                        'sancion_id' => $sancion->id,
                                    ]
                                ]);
                            }
                            $dia_sancion->addDay();

                            if ($sancion->user->persona->telefono == "+5493764735063") {
                                $sancion->user->persona->notify(new AvisoWppSancion($sancion));
                            }
                        }
                    }
                }
            }
        }
    }
}
