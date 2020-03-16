<?php

namespace App\Jobs;

use App\Models\BackpackUser;
use App\Models\DiaPreferencia;
use App\Models\DiaServicio;
use App\Models\Inscripcion;
use App\Models\MenuAsignado;
use App\Models\Sancion;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RegistrarInscripcionesJob implements ShouldQueue
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
        $users = BackpackUser::whereHas('persona', function ($query) {
            $query->where('comedor_id', $this->comedor_id);
        })->get();

        $mañana = Carbon::now()->addDay();

        $dia_servicio = DiaServicio::where('dia', $mañana->dayName)
            ->where('comedor_id', $this->comedor_id)
            ->first();

        if ($dia_servicio != null) {

            foreach ($users as $user) {

                $dia_preferencia = DiaPreferencia::where('user_id', $user->id)
                    ->where('dia_servicio_id', $dia_servicio->id)
                    ->first();

                $ma = MenuAsignado::where('user_id', $user->id)
                    ->where('comedor_id', $this->comedor_id)
                    ->whereDate('fecha_inicio', '<=', $mañana->toDateString())
                    ->whereDate('fecha_fin', '>=', $mañana->toDateString())
                    ->first();

                $existe_sancion = Sancion::where('comedor_id', '=', $this->comedor_id)
                    ->where('user_id', '=', $user->id)
                    ->whereDate('fecha', $mañana->toDateString())
                    ->where('activa', '=', 1)
                    ->count();

                if (($ma != null) and ($dia_preferencia != null) and ($existe_sancion==0)) {
                    if ($user->hasRole('comensal')) {
                        $ins = Inscripcion::create([
                            'fecha_inscripcion' => $mañana->toDateString(),
                            'retira' => $dia_preferencia->retira,
                            'user_id' => $user->id,
                            'banda_horaria_id' => $dia_preferencia->banda_horaria_id,
                            'menu_asignado_id' => $ma->id,
                            'comedor_id' => $user->persona->comedor_id,
                        ]);
                    }
                }
            }
        }
    }
}
