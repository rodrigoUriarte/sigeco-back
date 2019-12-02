<?php

namespace App\Console\Commands;

use App\Jobs\RegistrarInasistenciasJob;
use App\Models\Comedor;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class RegistrarInasistencias extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'registrar:inasistencias';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Registrar las inasistencias del dia';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $comedores = Comedor::all();

        foreach ($comedores as $comedor) {
            if ($comedor->bandasHorarias()->exists()) {

                $ubh = $comedor->bandasHorarias->sortByDesc('hora_fin')->first();
                $hora_fin = Carbon::parse($ubh->hora_fin)->format('H:i');
                $now = date("H:i");

                if ($now == $hora_fin) {
                    dispatch(new RegistrarInasistenciasJob($comedor->id));
                }
            }
        
        }
    }
}
