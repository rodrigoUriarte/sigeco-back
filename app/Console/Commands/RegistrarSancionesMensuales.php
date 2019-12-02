<?php

namespace App\Console\Commands;

use App\Jobs\RegistrarSancionesMensualesJob;
use App\Models\Comedor;
use Illuminate\Console\Command;

class RegistrarSancionesMensuales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'registrar:sancionesMensuales';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Registrar sanciones de tipo mensuales';

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
            dispatch(new RegistrarSancionesMensualesJob($comedor->id));
        }
    }
}
