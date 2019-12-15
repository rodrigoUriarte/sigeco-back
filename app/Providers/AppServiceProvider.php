<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\BandaHoraria;
use App\Models\IngresoInsumo;
use App\Models\Menu;
use App\Models\Plato;
use App\Models\Insumo;
use App\Models\Persona;
use App\Models\PlatoAsignado;
use App\Observers\BandaHorariaObserver;
use App\Observers\IngresoInsumoObserver;
use App\Observers\MenuObserver;
use App\Observers\PlatoObserver;
use App\Observers\InsumoObserver;
use App\Observers\PersonaObserver;
use App\Observers\PlatoAsignadoObserver;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

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
        IngresoInsumo::observe(IngresoInsumoObserver::class);
        PlatoAsignado::observe(PlatoAsignadoObserver::class);
        Persona::observe(PersonaObserver::class);

        /**
         * Paginate a standard Laravel Collection.
         *
         * @param int $perPage
         * @param int $total
         * @param int $page
         * @param string $pageName
         * @return array
         */
        Collection::macro('paginate', function ($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);
            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });
    }
}
