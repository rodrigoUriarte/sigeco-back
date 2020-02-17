<?php

namespace App\Console\Commands;

use App\Jobs\RegistrarMenusNoAsignadosJob;
use App\Models\Comedor;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RegistrarMenusNoAsignados extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'registrar:menusNoAsignados';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Agregar los menus asignados para el proximo mes a los usuarios que no lo hicieron';

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
            $lma = $comedor->parametro->limite_menu_asignado;
            $diaLimite = Carbon::now()->startOfMonth()->addDays($lma)->toDateString();

            if (Carbon::now()->toDateString() == $diaLimite) {
                dispatch(new RegistrarMenusNoAsignadosJob($comedor->id));
            }
        }
    }
}
