<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plato;

class PlatoController extends Controller
{

    // public function index(Request $request)
    // {
    //     $search_term = $request->input('plato_id');
    //     $page = $request->input('page');

    //     if ($search_term) {
    //         $results = Plato::where('descripcion', $search_term )->paginate(10);
    //     } else {
    //         $results = Plato::paginate(10);
    //     }

    //     return $results;
    // }

    public function index(Request $request)
    {
        $search_term = $request->input('plato_id');
        $form = collect($request->input('form'))
         ->pluck('value','name');

        $options = Plato::query();

        // if no category has been selected, show no options
        if (! $form['menu_id']) {
            return [];
        }

        // if a category has been selected, only show articles in that category
        if ($form['menu_id']) {
            $options = $options->where('menu_id', $form['menu_id']);
        }

        if ($search_term) {
            $results = $options->where('descripcion', 'LIKE', '%'.$search_term.'%')->paginate(10);
        } else {
            $results = $options->paginate(10);
        }

        return $options->paginate(10);
    }


    public function show($id)
    {
        return Category::find($id);
    }

}
