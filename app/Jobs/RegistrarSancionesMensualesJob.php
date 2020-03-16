<?php

namespace App\Jobs;

use App\Models\Asistencia;
use App\Models\BackpackUser;
use App\Models\Regla;
use App\Models\Sancion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

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

            $users = BackpackUser::whereHas('persona', function ($query) {
                $query
                    ->where('comedor_id', $this->comedor_id);
            })->get();

            foreach ($reglasM as $reglaM) {
                foreach ($users as $user) {
                    //coleccion de todas las inasistencias que no tienen una sancion asociada, de un determinado usuario de la ultima semana
                    $inasistencias = Asistencia::where('comedor_id', $this->comedor_id)
                        ->doesntHave('sancion')
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

                    if ($inasistencias->count() >= $reglaM->cantidad_faltas) {

                        $sancion = Sancion::create(
                            [
                                'desde' => Carbon::now()->nextWeekday()->toDateString(),
                                'hasta' => Carbon::now()->addWeekdays($reglaM->dias_sancion)->toDateString(),
                                'activa' => 1,
                                'user_id' => $user->id,
                                'comedor_id' => $user->persona->comedor_id,
                                'regla_id' => $reglaM->id,

                            ]
                        );

                        foreach ($inasistencias as $inasistencia) {
                            $inasistencia->sancion_id = $sancion->id;
                            $inasistencia->save();
                        }
                    }
                }
            }
        }
    }
}
