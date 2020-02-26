<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\RegistrarInasistencias',
        'App\Console\Commands\RegistrarSancionesMensuales',
        'App\Console\Commands\RegistrarSancionesSemanales',
        'App\Console\Commands\RegistrarMenusNoAsignados',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('registrar:inasistencia')
        ->everyMinute();
        $schedule->command('registrar:sancionesMensuales')
        ->monthlyOn(1, '00:00');
        $schedule->command('registrar:sancionesSemanales')
        ->weeklyOn(1, '00:00');
        $schedule->command('registrar:menusNoAsignados')
        ->dailyAt('00:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
