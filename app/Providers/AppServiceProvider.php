<?php

namespace App\Providers;

use App\Models\Asistencia;
use Illuminate\Support\ServiceProvider;

use App\Models\BandaHoraria;
use App\Models\Inscripcion;
use App\Models\Menu;
use App\Models\Plato;
use App\Models\Insumo;
use App\Models\Justificacion;
use App\Models\MenuAsignado;
use App\Models\Parametro;
use App\Models\Persona;
use App\Models\PlatoAsignado;
use App\Models\Remito;
use App\Observers\AsistenciaObserver;
use App\Observers\BandaHorariaObserver;
use App\Observers\InscripcionObserver;
use App\Observers\MenuObserver;
use App\Observers\PlatoObserver;
use App\Observers\InsumoObserver;
use App\Observers\JustificacionObserver;
use App\Observers\MenuAsignadoObserver;
use App\Observers\ParametroObserver;
use App\Observers\PersonaObserver;
use App\Observers\PlatoAsignadoObserver;
use App\Observers\RemitoObserver;
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
        PlatoAsignado::observe(PlatoAsignadoObserver::class);
        Persona::observe(PersonaObserver::class);
        Asistencia::observe(AsistenciaObserver::class);
        Justificacion::observe(JustificacionObserver::class);
        Parametro::observe(ParametroObserver::class);
        BandaHoraria::observe(BandaHorariaObserver::class);

        MenuAsignado::observe(MenuAsignadoObserver::class);
        Inscripcion::observe(InscripcionObserver::class);


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
