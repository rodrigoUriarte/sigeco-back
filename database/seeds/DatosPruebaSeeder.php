<?php

use Illuminate\Database\Seeder;

class DatosPruebaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(RolePermissionsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(UnidadAcademicaComedorOperativoSeeder::class);
        $this->call(ComensalesSeeder::class);
        $this->call(DiasPreferenciaSeeder::class);
        $this->call(MenusAsignadosSeeder::class);
        $this->call(InscripcionesSeeder::class);
        $this->call(AsistenciasSeeder::class);
        $this->call(GestionInsumosPlatosSeeder::class);
        //$this->call(IngresosInsumosSeeder::class);

    }
}
