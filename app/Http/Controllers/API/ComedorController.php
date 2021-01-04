<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComedorRequest;
use App\Http\Resources\ComedorCollectionResource;
use App\Http\Resources\ComedorResource;
use App\Models\Comedor;
use Illuminate\Http\Request;

class ComedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new ComedorCollectionResource(Comedor::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ComedorRequest $request)
    {
        $validated = $request->validated();

        $comedor = Comedor::create($request->all());

        return (new ComedorResource($comedor))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new ComedorResource(Comedor::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ComedorRequest $request, $id)
    {
        $validated = $request->validated();

        $comedor = Comedor::findOrFail($id);
        $comedor->update($request->all());

        return (new ComedorResource($comedor))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comedor = Comedor::findOrFail($id);
        $comedor->delete();

        return response()->json(null, 204);
    }
}
