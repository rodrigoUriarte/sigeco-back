<?php

namespace App\Jobs;

use App\Models\BackpackUser;
use App\Models\Comedor;
use App\Models\Menu;
use App\Models\MenuAsignado;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RegistrarMenusNoAsignadosJob implements ShouldQueue
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
        $lma = Comedor::find($this->comedor_id)->parametro->limite_menu_asignado;
        if (Carbon::now()->daysInMonth < $lma) {
            $lma = Carbon::now()->daysInMonth-1;
        } else {
            $lma = $lma -1;
        }
        $diaLimite = Carbon::now()->startOfMonth()->addDays($lma);
        $pdm = Carbon::now()->startOfMonth();

        $users = BackpackUser::whereHas('persona', function ($query) {
            $query->where('comedor_id', $this->comedor_id);
        })
        ->whereDoesntHave('menusAsignados', function ($query) use ($diaLimite,$pdm) {
            $query->where('created_at', '>=', $pdm);
            $query->where('created_at', '<=', $diaLimite);
        })
        ->get();

        foreach ($users as $user) {
            if ($user->hasRole('comensal')) {
                $uma = $user->menusAsignados->sortByDesc('fecha_fin')->first();

                $ma = MenuAsignado::create([
                    'fecha_inicio' => Carbon::parse('first day of next month')->toDateString(),
                    'fecha_fin' => Carbon::parse('last day of next month')->toDateString(),
                    'user_id' => $user->id,
                    'menu_id' => Menu::findOrFail($uma->menu_id)->id,
                    'comedor_id' => Comedor::findOrFail($user->persona->comedor->id)->id,
                ]);
            }
        }

    }
}
