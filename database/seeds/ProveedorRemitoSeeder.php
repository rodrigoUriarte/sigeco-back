<?php

use App\Models\Comedor;
use App\Models\InsumoRemito;
use App\Models\Lote;
use App\Models\Proveedor;
use App\Models\Remito;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ProveedorRemitoSeeder extends Seeder
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
        $proveedor = Proveedor::create([
            'nombre' => 'Pepito Sanchez',
            'telefono' => '3764124578',
            'email' => 'pepitosanchez@proveedor.com',
            'direccion' => 'Av. Uruguay 4321 Posadas Misiones'
        ]);

        DB::table('comedor_proveedor')->insert([
            'comedor_id' => Comedor::all()->first()->id,
            'proveedor_id' =>$proveedor->id
        ]);

        $remito1 = Remito::create([
            'fecha' => Carbon::now()->toDateString(),
            'numero' => 123456789012,
            'proveedor_id' => $proveedor->id,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumoRemito1 = InsumoRemito::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01', '2020-12-31'),
            'cantidad' => 20000,
            'insumo_id' => 1,
            'remito_id' => $remito1->id,
        ]);

        $lote1 = Lote::create([
            'fecha_vencimiento' => $insumoRemito1->fecha_vencimiento,
            'cantidad' => $insumoRemito1->cantidad,
            'usado' => false,
            'comedor_id' => Comedor::all()->first()->id,
            'insumo_id' => $insumoRemito1->insumo_id,
            'insumo_remito_id' => $insumoRemito1->id,
        ]);

        $insumoRemito2 = InsumoRemito::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01', '2020-12-31'),
            'cantidad' => 5000,
            'insumo_id' => 1,
            'remito_id' => $remito1->id,
        ]);

        $lote2 = Lote::create([
            'fecha_vencimiento' => $insumoRemito2->fecha_vencimiento,
            'cantidad' => $insumoRemito2->cantidad,
            'usado' => false,
            'comedor_id' => Comedor::all()->first()->id,
            'insumo_id' => $insumoRemito2->insumo_id,
            'insumo_remito_id' => $insumoRemito2->id,
        ]);


        $insumoRemito3 = InsumoRemito::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01', '2020-12-31'),
            'cantidad' => 10000,
            'insumo_id' => 2,
            'remito_id' => $remito1->id,
        ]);

        $lote3 = Lote::create([
            'fecha_vencimiento' => $insumoRemito3->fecha_vencimiento,
            'cantidad' => $insumoRemito3->cantidad,
            'usado' => false,
            'comedor_id' => Comedor::all()->first()->id,
            'insumo_id' => $insumoRemito3->insumo_id,
            'insumo_remito_id' => $insumoRemito3->id,
        ]);

        $insumoRemito4 = InsumoRemito::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01', '2020-12-31'),
            'cantidad' => 15000,
            'insumo_id' => 2,
            'remito_id' => $remito1->id,
        ]);

        $lote4 = Lote::create([
            'fecha_vencimiento' => $insumoRemito4->fecha_vencimiento,
            'cantidad' => $insumoRemito4->cantidad,
            'usado' => false,
            'comedor_id' => Comedor::all()->first()->id,
            'insumo_id' => $insumoRemito4->insumo_id,
            'insumo_remito_id' => $insumoRemito4->id,
        ]);

        $insumoRemito5 = InsumoRemito::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01', '2020-12-31'),
            'cantidad' => 20000,
            'insumo_id' => 3,
            'remito_id' => $remito1->id,
        ]);

        $lote5 = Lote::create([
            'fecha_vencimiento' => $insumoRemito5->fecha_vencimiento,
            'cantidad' => $insumoRemito5->cantidad,
            'usado' => false,
            'comedor_id' => Comedor::all()->first()->id,
            'insumo_id' => $insumoRemito5->insumo_id,
            'insumo_remito_id' => $insumoRemito5->id,
        ]);

        $insumoRemito6 = InsumoRemito::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01', '2020-12-31'),
            'cantidad' => 30000,
            'insumo_id' => 4,
            'remito_id' => $remito1->id,
        ]);

        $lote6 = Lote::create([
            'fecha_vencimiento' => $insumoRemito6->fecha_vencimiento,
            'cantidad' => $insumoRemito6->cantidad,
            'usado' => false,
            'comedor_id' => Comedor::all()->first()->id,
            'insumo_id' => $insumoRemito6->insumo_id,
            'insumo_remito_id' => $insumoRemito6->id,
        ]);

        $insumoRemito7 = InsumoRemito::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01', '2020-12-31'),
            'cantidad' => 50000,
            'insumo_id' => 5,
            'remito_id' => $remito1->id,
        ]);

        $lote7 = Lote::create([
            'fecha_vencimiento' => $insumoRemito7->fecha_vencimiento,
            'cantidad' => $insumoRemito7->cantidad,
            'usado' => false,
            'comedor_id' => Comedor::all()->first()->id,
            'insumo_id' => $insumoRemito7->insumo_id,
            'insumo_remito_id' => $insumoRemito7->id,
        ]);

        $insumoRemito8 = InsumoRemito::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01', '2020-12-31'),
            'cantidad' => 40000,
            'insumo_id' => 6,
            'remito_id' => $remito1->id,
        ]);

        $lote8 = Lote::create([
            'fecha_vencimiento' => $insumoRemito8->fecha_vencimiento,
            'cantidad' => $insumoRemito8->cantidad,
            'usado' => false,
            'comedor_id' => Comedor::all()->first()->id,
            'insumo_id' => $insumoRemito8->insumo_id,
            'insumo_remito_id' => $insumoRemito8->id,
        ]);

        $insumoRemito9 = InsumoRemito::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01', '2020-12-31'),
            'cantidad' => 100,
            'insumo_id' => 7,
            'remito_id' => $remito1->id,
        ]);

        $lote9 = Lote::create([
            'fecha_vencimiento' => $insumoRemito9->fecha_vencimiento,
            'cantidad' => $insumoRemito9->cantidad,
            'usado' => false,
            'comedor_id' => Comedor::all()->first()->id,
            'insumo_id' => $insumoRemito9->insumo_id,
            'insumo_remito_id' => $insumoRemito9->id,
        ]);

        $insumoRemito10 = InsumoRemito::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01', '2020-12-31'),
            'cantidad' => 15000,
            'insumo_id' => 8,
            'remito_id' => $remito1->id,
        ]);

        $lote10 = Lote::create([
            'fecha_vencimiento' => $insumoRemito10->fecha_vencimiento,
            'cantidad' => $insumoRemito10->cantidad,
            'usado' => false,
            'comedor_id' => Comedor::all()->first()->id,
            'insumo_id' => $insumoRemito10->insumo_id,
            'insumo_remito_id' => $insumoRemito10->id,
        ]);

        $insumoRemito11 = InsumoRemito::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01', '2020-12-31'),
            'cantidad' => 250,
            'insumo_id' => 9,
            'remito_id' => $remito1->id,
        ]);

        $lote11 = Lote::create([
            'fecha_vencimiento' => $insumoRemito11->fecha_vencimiento,
            'cantidad' => $insumoRemito11->cantidad,
            'usado' => false,
            'comedor_id' => Comedor::all()->first()->id,
            'insumo_id' => $insumoRemito11->insumo_id,
            'insumo_remito_id' => $insumoRemito11->id,
        ]);

        $insumoRemito12 = InsumoRemito::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01', '2020-12-31'),
            'cantidad' => 10000,
            'insumo_id' => 10,
            'remito_id' => $remito1->id,
        ]);

        $lote12 = Lote::create([
            'fecha_vencimiento' => $insumoRemito12->fecha_vencimiento,
            'cantidad' => $insumoRemito12->cantidad,
            'usado' => false,
            'comedor_id' => Comedor::all()->first()->id,
            'insumo_id' => $insumoRemito12->insumo_id,
            'insumo_remito_id' => $insumoRemito12->id,
        ]);

        // NO HACEMOS INGRESO DE MORRON(ID-11) PARA PROBAR ALGUNAS FUNCIONES DEL SISTEMA
        $insumoRemito14 = InsumoRemito::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01', '2020-12-31'),
            'cantidad' => 10000,
            'insumo_id' => 12,
            'remito_id' => $remito1->id,
        ]);

        $lote14 = Lote::create([
            'fecha_vencimiento' => $insumoRemito14->fecha_vencimiento,
            'cantidad' => $insumoRemito14->cantidad,
            'usado' => false,
            'comedor_id' => Comedor::all()->first()->id,
            'insumo_id' => $insumoRemito14->insumo_id,
            'insumo_remito_id' => $insumoRemito14->id,
        ]);

        $insumoRemito15 = InsumoRemito::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01', '2020-12-31'),
            'cantidad' => 20,
            'insumo_id' => 13,
            'remito_id' => $remito1->id,
        ]);

        $lote15 = Lote::create([
            'fecha_vencimiento' => $insumoRemito15->fecha_vencimiento,
            'cantidad' => $insumoRemito15->cantidad,
            'usado' => false,
            'comedor_id' => Comedor::all()->first()->id,
            'insumo_id' => $insumoRemito15->insumo_id,
            'insumo_remito_id' => $insumoRemito15->id,
        ]);

        $insumoRemito16 = InsumoRemito::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01', '2020-12-31'),
            'cantidad' => 200,
            'insumo_id' => 14,
            'remito_id' => $remito1->id,
        ]);

        $lote16 = Lote::create([
            'fecha_vencimiento' => $insumoRemito16->fecha_vencimiento,
            'cantidad' => $insumoRemito16->cantidad,
            'usado' => false,
            'comedor_id' => Comedor::all()->first()->id,
            'insumo_id' => $insumoRemito16->insumo_id,
            'insumo_remito_id' => $insumoRemito16->id,
        ]);

        $insumoRemito17 = InsumoRemito::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01', '2020-12-31'),
            'cantidad' => 12000,
            'insumo_id' => 15,
            'remito_id' => $remito1->id,
        ]);

        $lote17 = Lote::create([
            'fecha_vencimiento' => $insumoRemito17->fecha_vencimiento,
            'cantidad' => $insumoRemito17->cantidad,
            'usado' => false,
            'comedor_id' => Comedor::all()->first()->id,
            'insumo_id' => $insumoRemito17->insumo_id,
            'insumo_remito_id' => $insumoRemito17->id,
        ]);

        $insumoRemito18 = InsumoRemito::create([
            'fecha_vencimiento' => $this->rand_date('2020-06-01', '2020-12-31'),
            'cantidad' => 15000,
            'insumo_id' => 16,
            'remito_id' => $remito1->id,
        ]);

        $lote18 = Lote::create([
            'fecha_vencimiento' => $insumoRemito18->fecha_vencimiento,
            'cantidad' => $insumoRemito18->cantidad,
            'usado' => false,
            'comedor_id' => Comedor::all()->first()->id,
            'insumo_id' => $insumoRemito18->insumo_id,
            'insumo_remito_id' => $insumoRemito18->id,
        ]);
    }
}
