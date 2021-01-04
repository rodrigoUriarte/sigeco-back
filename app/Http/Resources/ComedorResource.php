<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ComedorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'descripcion' => $this->descripcion,
            'direccion' => $this->direccion,
            'unidad_academica_id' => new UnidadAcademicaResource($this->whenLoaded('unidadAcademica')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
