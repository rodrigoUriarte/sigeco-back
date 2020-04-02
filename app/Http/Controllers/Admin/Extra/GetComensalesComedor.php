<?php

namespace App\Http\Controllers\Admin\Extra;

use App\Http\Controllers\Controller;
use App\Models\BackpackUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\Input;

class GetComensalesComedor extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $page = $request->input('page');
            $resultCount = 10;

            $offset = ($page - 1) * $resultCount;

            $comensales = BackpackUser::whereHas('persona', function ($query) {
                $query->where('comedor_id', backpack_user()->persona->comedor_id);
                })
                ->whereHas('roles', function ($query) {
                    $query->where('name', 'comensal');
                })
                ->where('name', 'LIKE',  '%' . $request->input('term') . '%')
                ->orderBy('name')
                ->skip($offset)
                ->take($resultCount)
                ->get(['id', DB::raw('name as text')]);

            $count = BackpackUser::whereHas('persona', function ($query) use ($request, $offset, $resultCount) {
                $query->where('comedor_id', backpack_user()->persona->comedor_id);
                })
                ->whereHas('roles', function ($query) {
                    $query->where('name', 'comensal');
                })
                ->where('name', 'LIKE',  '%' . $request->input('term') . '%')
                ->count();

            $endCount = $offset + $resultCount;
            $morePages =  $count > $endCount;

            $results = array(
                "results" => $comensales,
                "pagination" => array(
                    "more" => $morePages
                )
            );

            return response()->json($results);
        }
    }


    // public function show($id)
    // {
    //     return Category::find($id);
    // }
}
