<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;

use App\Models\BandaHoraria;
use App\Models\Menu;
use App\Models\Plato;
use App\Models\Insumo;
use App\Observers\BandaHorariaObserver;
use App\Observers\MenuObserver;
use App\Observers\PlatoObserver;
use App\Observers\InsumoObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Menu::observe(MenuObserver::class);
        // Plato::observe(PlatoObserver::class);
        // BandaHoraria::observe(BandaHorariaObserver::class);
        // Insumo::observe(InsumoObserver::class);

    }
}
