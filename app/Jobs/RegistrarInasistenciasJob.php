<?php

namespace App\Jobs;

use App\Models\Asistencia;
use App\Models\Inscripcion;
use App\Notifications\AvisoWppInasistencia;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class RegistrarInasistenciasJob implements ShouldQueue
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
        $inscripciones = Inscripcion::where('fecha_inscripcion', Carbon::now()->toDateString())
            ->where('comedor_id', $this->comedor_id)
            ->doesntHave('asistencia')
            ->get();
        //necesito comprobar de que comedor son las inscripciones??

        foreach ($inscripciones as $inscripcion) {
            $asistencia = Asistencia::create(
                [
                    'asistio' => false,
                    'asistencia_fbh' => false,
                    'inscripcion_id' => $inscripcion->id,
                    'comedor_id' => $inscripcion->user->persona->comedor_id
                ]
                
            );
            if ($inscripcion->user->persona->telefono == "+5493764735063") {
                $inscripcion->user->persona->notify(new AvisoWppInasistencia($asistencia));
            }
        }
    }
}
