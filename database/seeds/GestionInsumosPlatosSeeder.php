<?php

use App\Models\Comedor;
use App\Models\Insumo;
use App\Models\InsumoPlato;
use App\Models\Menu;
use App\Models\Plato;
use Illuminate\Database\Seeder;

class GestionInsumosPlatosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plato1 = Plato::create([
            'descripcion' => 'GUISO DE ARROZ',
            'menu_id' => Menu::all()->first()->id,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $plato2 = Plato::create([
            'descripcion' => 'GUISO DE FIDEO',
            'menu_id' => Menu::all()->first()->id,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $plato3 = Plato::create([
            'descripcion' => 'POLLO AL HORNO CON PAPAS',
            'menu_id' => Menu::all()->first()->id,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $plato4 = Plato::create([
            'descripcion' => 'ESTOFADO DE LENTEJAS',
            'menu_id' => Menu::all()->first()->id,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $plato5 = Plato::create([
            'descripcion' => 'MILANESA DE POLLO CON PURE DE PAPA',
            'menu_id' => Menu::all()->first()->id,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $plato6 = Plato::create([
            'descripcion' => 'FIDEOS CON SALSA BOLOGNESA',
            'menu_id' => Menu::all()->first()->id,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumo1 = Insumo::create([
            'descripcion' => 'ARROZ',
            'unidad_medida' => 'GRAMOS',
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumo2 = Insumo::create([
            'descripcion' => 'PAPA',
            'unidad_medida' => 'GRAMOS',
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumo3 = Insumo::create([
            'descripcion' => 'ZAPALLO',
            'unidad_medida' => 'GRAMOS',
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumo4 = Insumo::create([
            'descripcion' => 'CARNE MOLIDA',
            'unidad_medida' => 'GRAMOS',
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumo5 = Insumo::create([
            'descripcion' => 'SALSA DE TOMATE',
            'unidad_medida' => 'GRAMOS',
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumo6 = Insumo::create([
            'descripcion' => 'FIDEOS SECOS',
            'unidad_medida' => 'GRAMOS',
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumo7 = Insumo::create([
            'descripcion' => 'PATA MUSLO DE POLLO',
            'unidad_medida' => 'UNIDAD',
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumo8 = Insumo::create([
            'descripcion' => 'LENTEJAS SECAS',
            'unidad_medida' => 'GRAMOS',
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumo9 = Insumo::create([
            'descripcion' => 'MILANESAS DE POLLO',
            'unidad_medida' => 'UNIDAD',
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumo10 = Insumo::create([
            'descripcion' => 'CEBOLLA',
            'unidad_medida' => 'GRAMOS',
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumo11 = Insumo::create([
            'descripcion' => 'MORRON',
            'unidad_medida' => 'GRAMOS',
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumoPlato1 = InsumoPlato::create([
            'plato_id' => $plato1->id,
            'insumo_id' => $insumo1->id,
            'cantidad' => 50,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumoPlato2 = InsumoPlato::create([
            'plato_id' => $plato1->id,
            'insumo_id' => $insumo2->id,
            'cantidad' => 100,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumoPlato3 = InsumoPlato::create([
            'plato_id' => $plato1->id,
            'insumo_id' => $insumo3->id,
            'cantidad' => 80,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumoPlato4 = InsumoPlato::create([
            'plato_id' => $plato1->id,
            'insumo_id' => $insumo5->id,
            'cantidad' => 50,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumoPlato5 = InsumoPlato::create([
            'plato_id' => $plato1->id,
            'insumo_id' => $insumo7->id,
            'cantidad' => 1,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumoPlato6 = InsumoPlato::create([
            'plato_id' => $plato2->id,
            'insumo_id' => $insumo6->id,
            'cantidad' => 50,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumoPlato7 = InsumoPlato::create([
            'plato_id' => $plato2->id,
            'insumo_id' => $insumo2->id,
            'cantidad' => 100,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumoPlato8 = InsumoPlato::create([
            'plato_id' => $plato2->id,
            'insumo_id' => $insumo3->id,
            'cantidad' => 80,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumoPlato9 = InsumoPlato::create([
            'plato_id' => $plato2->id,
            'insumo_id' => $insumo5->id,
            'cantidad' => 50,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumoPlato10 = InsumoPlato::create([
            'plato_id' => $plato2->id,
            'insumo_id' => $insumo7->id,
            'cantidad' => 1,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumoPlato11 = InsumoPlato::create([
            'plato_id' => $plato3->id,
            'insumo_id' => $insumo7->id,
            'cantidad' => 1,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumoPlato12 = InsumoPlato::create([
            'plato_id' => $plato3->id,
            'insumo_id' => $insumo2->id,
            'cantidad' => 200,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumoPlato13 = InsumoPlato::create([
            'plato_id' => $plato4->id,
            'insumo_id' => $insumo8->id,
            'cantidad' => 50,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumoPlato14 = InsumoPlato::create([
            'plato_id' => $plato4->id,
            'insumo_id' => $insumo2->id,
            'cantidad' => 100,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumoPlato15 = InsumoPlato::create([
            'plato_id' => $plato4->id,
            'insumo_id' => $insumo3->id,
            'cantidad' => 80,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumoPlato16 = InsumoPlato::create([
            'plato_id' => $plato4->id,
            'insumo_id' => $insumo5->id,
            'cantidad' => 50,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumoPlato17 = InsumoPlato::create([
            'plato_id' => $plato4->id,
            'insumo_id' => $insumo4->id,
            'cantidad' => 50,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumoPlato18 = InsumoPlato::create([
            'plato_id' => $plato5->id,
            'insumo_id' => $insumo9->id,
            'cantidad' => 1,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumoPlato19 = InsumoPlato::create([
            'plato_id' => $plato5->id,
            'insumo_id' => $insumo2->id,
            'cantidad' => 200,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumoPlato20 = InsumoPlato::create([
            'plato_id' => $plato6->id,
            'insumo_id' => $insumo6->id,
            'cantidad' => 75,
            'comedor_id' => Comedor::all()->first()->id,
        ]);
        
        $insumoPlato21 = InsumoPlato::create([
            'plato_id' => $plato6->id,
            'insumo_id' => $insumo5->id,
            'cantidad' => 50,
            'comedor_id' => Comedor::all()->first()->id,
        ]);
        
        $insumoPlato22 = InsumoPlato::create([
            'plato_id' => $plato6->id,
            'insumo_id' => $insumo4->id,
            'cantidad' => 50,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        
    }
}
