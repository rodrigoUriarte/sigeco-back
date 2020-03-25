<?php

use App\Models\Comedor;
use App\Models\IngresoInsumo;
use Illuminate\Database\Seeder;

class IngresosInsumosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    function rand_date($min_date, $max_date)
    {
        /* Gets 2 dates as string, earlier and later date.
           Returns date in between them.
        */

        $min_epoch = strtotime($min_date);
        $max_epoch = strtotime($max_date);

        $rand_epoch = rand($min_epoch, $max_epoch);

        return date('Y-m-d', $rand_epoch);
    }
    public function run()
    {
        IngresoInsumo::enableAuditing();
        
        $ingresoInsumo1 = IngresoInsumo::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01','2020-12-31'),
            'cantidad' => 20000,
            'insumo_id' => 1,
            'comedor_id' => Comedor::all()->first()->id,
            'user_id' => 3,
        ]);

        $ingresoInsumo2 = IngresoInsumo::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01','2020-12-31'),
            'cantidad' => 5000,
            'insumo_id' => 1,
            'comedor_id' => Comedor::all()->first()->id,
            'user_id' => 3,
        ]);

        $ingresoInsumo3 = IngresoInsumo::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01','2020-12-31'),
            'cantidad' => 10000,
            'insumo_id' => 2,
            'comedor_id' => Comedor::all()->first()->id,
            'user_id' => 3,
        ]);

        $ingresoInsumo4 = IngresoInsumo::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01','2020-12-31'),
            'cantidad' => 15000,
            'insumo_id' => 2,
            'comedor_id' => Comedor::all()->first()->id,
            'user_id' => 3,
        ]);

        $ingresoInsumo5 = IngresoInsumo::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01','2020-12-31'),
            'cantidad' => 20000,
            'insumo_id' => 3,
            'comedor_id' => Comedor::all()->first()->id,
            'user_id' => 3,
        ]);

        $ingresoInsumo6 = IngresoInsumo::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01','2020-12-31'),
            'cantidad' => 30000,
            'insumo_id' => 4,
            'comedor_id' => Comedor::all()->first()->id,
            'user_id' => 3,
        ]);

        $ingresoInsumo7 = IngresoInsumo::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01','2020-12-31'),
            'cantidad' => 50000,
            'insumo_id' => 5,
            'comedor_id' => Comedor::all()->first()->id,
            'user_id' => 3,
        ]);

        $ingresoInsumo8 = IngresoInsumo::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01','2020-12-31'),
            'cantidad' => 40000,
            'insumo_id' => 6,
            'comedor_id' => Comedor::all()->first()->id,
            'user_id' => 3,
        ]);

        $ingresoInsumo9 = IngresoInsumo::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01','2020-12-31'),
            'cantidad' => 100,
            'insumo_id' => 7,
            'comedor_id' => Comedor::all()->first()->id,
            'user_id' => 3,
        ]);

        $ingresoInsumo10 = IngresoInsumo::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01','2020-12-31'),
            'cantidad' => 15000,
            'insumo_id' => 8,
            'comedor_id' => Comedor::all()->first()->id,
            'user_id' => 3,
        ]);

        $ingresoInsumo11 = IngresoInsumo::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01','2020-12-31'),
            'cantidad' => 250,
            'insumo_id' => 9,
            'comedor_id' => Comedor::all()->first()->id,
            'user_id' => 3,
        ]);

        $ingresoInsumo12 = IngresoInsumo::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01','2020-12-31'),
            'cantidad' => 10000,
            'insumo_id' => 10,
            'comedor_id' => Comedor::all()->first()->id,
            'user_id' => 3,
        ]);

        // NO HACEMOS INGRESO DE MORRON(ID-11) PARA PROBAR ALGUNAS FUNCIONES DEL SISTEMA
        $ingresoInsumo14 = IngresoInsumo::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01','2020-12-31'),
            'cantidad' => 10000,
            'insumo_id' => 12,
            'comedor_id' => Comedor::all()->first()->id,
            'user_id' => 3,
        ]);

        $ingresoInsumo15 = IngresoInsumo::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01','2020-12-31'),
            'cantidad' => 20,
            'insumo_id' => 13,
            'comedor_id' => Comedor::all()->first()->id,
            'user_id' => 3,
        ]);

        $ingresoInsumo16 = IngresoInsumo::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01','2020-12-31'),
            'cantidad' => 200,
            'insumo_id' => 14,
            'comedor_id' => Comedor::all()->first()->id,
            'user_id' => 3,
        ]);

        $ingresoInsumo17 = IngresoInsumo::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01','2020-12-31'),
            'cantidad' => 12000,
            'insumo_id' => 15,
            'comedor_id' => Comedor::all()->first()->id,
            'user_id' => 3,
        ]);

        
        $ingresoInsumo17 = IngresoInsumo::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01','2020-12-31'),
            'cantidad' => 15000,
            'insumo_id' => 16,
            'comedor_id' => Comedor::all()->first()->id,
            'user_id' => 3,
        ]);

    }
}
