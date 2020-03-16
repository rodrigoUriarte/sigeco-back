<?php

namespace App\Console\Commands;

use App\Jobs\RegistrarInscripcionesJob;
use App\Models\Comedor;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RegistrarInscripciones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'registrar:inscripciones';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Agregar las inscripciones en base a las preferencias de cada comensal';

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
            if ($comedor->parametro()->exists()) {

                $li = $comedor->parametro->limite_inscripcion;
                $li = Carbon::parse($li)->format('H:i');
                $now = Carbon::now()->format("H:i"); 

                if ($now == $li) {
                    dispatch(new RegistrarInscripcionesJob($comedor->id));
                }
            }
        
        }
    }
}
