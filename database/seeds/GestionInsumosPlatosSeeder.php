<?php

use App\Models\Comedor;
use App\Models\Insumo;
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
            'menu_id' => Menu::where('descripcion','MENU NORMAL')->first()->id,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $plato2 = Plato::create([
            'descripcion' => 'GUISO DE FIDEO',
            'menu_id' => Menu::where('descripcion','MENU NORMAL')->first()->id,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $plato3 = Plato::create([
            'descripcion' => 'POLLO AL HORNO CON PAPAS',
            'menu_id' => Menu::where('descripcion','MENU NORMAL')->first()->id,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $plato4 = Plato::create([
            'descripcion' => 'ESTOFADO DE LENTEJAS',
            'menu_id' => Menu::where('descripcion','MENU NORMAL')->first()->id,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $plato5 = Plato::create([
            'descripcion' => 'MILANESA DE POLLO CON PURE DE PAPA',
            'menu_id' => Menu::where('descripcion','MENU NORMAL')->first()->id,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $plato6 = Plato::create([
            'descripcion' => 'FIDEOS CON SALSA BOLOGNESA',
            'menu_id' => Menu::where('descripcion','MENU NORMAL')->first()->id,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $plato7 = Plato::create([
            'descripcion' => 'TARTA DE VERDURAS',
            'menu_id' => Menu::where('descripcion','MENU DIETA')->first()->id,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $plato8 = Plato::create([
            'descripcion' => 'SALPICON DE AVE',
            'menu_id' => Menu::where('descripcion','MENU DIETA')->first()->id,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $plato9 = Plato::create([
            'descripcion' => 'ENSALADA DE LENTEJAS MIXTA',
            'menu_id' => Menu::where('descripcion','MENU DIETA')->first()->id,
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumo1 = Insumo::create([
            'descripcion' => 'ARROZ',
            'unidad_medida' => 'GRAMO/S',
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumo2 = Insumo::create([
            'descripcion' => 'PAPA',
            'unidad_medida' => 'GRAMO/S',
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumo3 = Insumo::create([
            'descripcion' => 'ZAPALLO',
            'unidad_medida' => 'GRAMO/S',
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumo4 = Insumo::create([
            'descripcion' => 'CARNE MOLIDA',
            'unidad_medida' => 'GRAMO/S',
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumo5 = Insumo::create([
            'descripcion' => 'SALSA DE TOMATE',
            'unidad_medida' => 'GRAMO/S',
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumo6 = Insumo::create([
            'descripcion' => 'FIDEOS SECOS',
            'unidad_medida' => 'GRAMO/S',
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumo7 = Insumo::create([
            'descripcion' => 'PATA MUSLO DE POLLO',
            'unidad_medida' => 'UNIDAD/ES',
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumo8 = Insumo::create([
            'descripcion' => 'LENTEJAS SECAS',
            'unidad_medida' => 'GRAMO/S',
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumo9 = Insumo::create([
            'descripcion' => 'MILANESAS DE POLLO',
            'unidad_medida' => 'UNIDAD/ES',
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumo10 = Insumo::create([
            'descripcion' => 'CEBOLLA',
            'unidad_medida' => 'GRAMO/S',
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumo11 = Insumo::create([
            'descripcion' => 'MORRON',
            'unidad_medida' => 'GRAMO/S',
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumo12 = Insumo::create([
            'descripcion' => 'ACELGA',
            'unidad_medida' => 'GRAMO/S',
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumo13 = Insumo::create([
            'descripcion' => 'TAPA PASCUALINA',
            'unidad_medida' => 'UNIDAD/ES',
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumo14 = Insumo::create([
            'descripcion' => 'HUEVOS',
            'unidad_medida' => 'UNIDAD/ES',
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumo15 = Insumo::create([
            'descripcion' => 'REPOLLO',
            'unidad_medida' => 'GRAMO/S',
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $insumo16 = Insumo::create([
            'descripcion' => 'TOMATE',
            'unidad_medida' => 'GRAMO/S',
            'comedor_id' => Comedor::all()->first()->id,
        ]);

        $plato1->insumos()->attach($insumo1->id, ['cantidad' => 50]);
        $plato1->insumos()->attach($insumo2->id, ['cantidad' => 100]);
        $plato1->insumos()->attach($insumo3->id, ['cantidad' => 80]);
        $plato1->insumos()->attach($insumo5->id, ['cantidad' => 50]);
        $plato1->insumos()->attach($insumo7->id, ['cantidad' => 1]);
        $plato2->insumos()->attach($insumo6->id, ['cantidad' => 50]);
        $plato2->insumos()->attach($insumo2->id, ['cantidad' => 100]);
        $plato2->insumos()->attach($insumo3->id, ['cantidad' => 80]);
        $plato2->insumos()->attach($insumo5->id, ['cantidad' => 50]);
        $plato2->insumos()->attach($insumo1->id, ['cantidad' => 50]);
        $plato2->insumos()->attach($insumo7->id, ['cantidad' => 1]);
        $plato3->insumos()->attach($insumo7->id, ['cantidad' => 1]);
        $plato3->insumos()->attach($insumo2->id, ['cantidad' => 200]);
        $plato4->insumos()->attach($insumo8->id, ['cantidad' => 50]);
        $plato4->insumos()->attach($insumo2->id, ['cantidad' => 100]);
        $plato4->insumos()->attach($insumo3->id, ['cantidad' => 80]);
        $plato4->insumos()->attach($insumo5->id, ['cantidad' => 50]);
        $plato4->insumos()->attach($insumo4->id, ['cantidad' => 50]);
        $plato5->insumos()->attach($insumo9->id, ['cantidad' => 1]);
        $plato5->insumos()->attach($insumo2->id, ['cantidad' => 200]);
        $plato6->insumos()->attach($insumo6->id, ['cantidad' => 75]);
        $plato6->insumos()->attach($insumo5->id, ['cantidad' => 50]);
        $plato6->insumos()->attach($insumo4->id, ['cantidad' => 50]);
        $plato7->insumos()->attach($insumo10->id, ['cantidad' => 50]);
        $plato7->insumos()->attach($insumo11->id, ['cantidad' => 20]);
        $plato7->insumos()->attach($insumo12->id, ['cantidad' => 100]);
        $plato7->insumos()->attach($insumo13->id, ['cantidad' => 0.25]);
        $plato7->insumos()->attach($insumo14->id, ['cantidad' => 2]);
        $plato8->insumos()->attach($insumo1->id, ['cantidad' => 50]);
        $plato8->insumos()->attach($insumo7->id, ['cantidad' => 1]);
        $plato8->insumos()->attach($insumo11->id, ['cantidad' => 20]);
        $plato8->insumos()->attach($insumo14->id, ['cantidad' => 1]);
        $plato8->insumos()->attach($insumo15->id, ['cantidad' => 50]);
        $plato8->insumos()->attach($insumo16->id, ['cantidad' => 50]);
        $plato9->insumos()->attach($insumo8->id, ['cantidad' => 100]);
        $plato9->insumos()->attach($insumo10->id, ['cantidad' => 50]);
        $plato9->insumos()->attach($insumo11->id, ['cantidad' => 20]);
        $plato9->insumos()->attach($insumo14->id, ['cantidad' => 1]);
        $plato9->insumos()->attach($insumo16->id, ['cantidad' => 50]);
    }
}
