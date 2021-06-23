<?php

use App\User;
use App\Models\BandaHoraria;
use App\Models\Comedor;
use App\Models\DiaServicio;
use App\Models\Menu;
use App\Models\Parametro;
use App\Models\Persona;
use App\Models\UnidadAcademica;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnidadAcademicaComedorOperativoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function factoryWithoutObservers($class, $name = 'default')
    {
        $class::flushEventListeners();
        return factory($class, $name);
    }
    public function run()
    {
        $ua = UnidadAcademica::create([
            'nombre' => 'FACULTAD DE CIENCIAS EXACTAS QUIMICAS Y NATURALES'
        ]);

        $comedor = Comedor::create([
            'descripcion' => 'COMEDOR APOSTOLES',
            'direccion' => 'Pellegrini 269, Apóstoles, Misiones, Argentina',
            'unidad_academica_id' => $ua->id,
        ]);

        $parametro = Parametro::create([
            'limite_inscripcion' => '21:00:00',
            'limite_menu_asignado' => 15,
            'retirar' => 1,
            'comedor_id' => $comedor->id,
        ]);

        DB::table('dias_servicio')->insert([
            [
                'dia' => 'lunes',
                'comedor_id' => $comedor->id,
            ],
            [
                'dia' => 'martes',
                'comedor_id' => $comedor->id,
            ],
            [
                'dia' => 'miércoles',
                'comedor_id' => $comedor->id,
            ],
            [
                'dia' => 'jueves',
                'comedor_id' => $comedor->id,
            ],
            [
                'dia' => 'viernes',
                'comedor_id' => $comedor->id,
            ],
        ]);

        DB::table('bandas_horarias')->insert([
            [
                'descripcion' => '14:00:00 - 14:30:00',
                'hora_inicio' => '14:00:00',
                'hora_fin' => '14:30:00',
                'limite_comensales' => '100',
                'comedor_id' => $comedor->id,
            ],

            [
                'descripcion' => '14:30:00 - 15:00:00',
                'hora_inicio' => '14:30:00',
                'hora_fin' => '15:00:00',
                'limite_comensales' => '100',
                'comedor_id' => $comedor->id,
            ],
        ]);

        DB::table('reglas')->insert([
            [
                'descripcion' => '1 falta por semana, 1 día de sanción.',
                'cantidad_faltas' => 1,
                'tiempo' => 'semana',
                'dias_sancion' => 1,
                'comedor_id' => $comedor->id,
            ],

            [
                'descripcion' => '4 falta por mes, 5 días de sanción.',
                'cantidad_faltas' => 4,
                'tiempo' => 'mes',
                'dias_sancion' => 5,
                'comedor_id' => $comedor->id,
            ],
        ]);

        DB::table('menus')->insert([
            ['descripcion' => 'MENU NORMAL',
            'comedor_id' => $comedor->id,],
            ['descripcion' => 'MENU DIETA',
            'comedor_id' => $comedor->id,],
        ]);


        $operativo = new Persona();
        $dispatcher = Persona::getEventDispatcher();
        Persona::unsetEventDispatcher();
        $operativo = $operativo->create([
            'dni' => '11111111',
            'nombre' => 'Operativo',
            'apellido' => 'Apostoles',
            'telefono' => '3758875421',
            'email' => 'operativoapostoles@comedor.com',
            'comedor_id' => $comedor->id,
            'unidad_academica_id' => $ua->id
        ]);
        Persona::setEventDispatcher($dispatcher);

        $user = User::create([
            'name' => $operativo->nombre . $operativo->apellido,
            'email' => $operativo->email,
            'password' => bcrypt($operativo->dni),
            'persona_id' => $operativo->id,
        ]);

        DB::table('model_has_roles')->insert([
            [
                'role_id' => '3',
                'model_type' => 'App\User',
                'model_id' => $user->id,
            ]
        ]);
    }
}
