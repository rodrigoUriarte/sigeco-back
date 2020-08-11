<?php

namespace App\Rules;

use App\Models\Insumo;
use App\Models\Plato;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class PlatoUnicoRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($id, $comedor_id)
    {
        $this->id = $id;
        $this->comedor_id = $comedor_id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $insumos = Insumo::whereIn('id', Arr::pluck($value, 'insumos'))->get();

        $platos = Plato::where('comedor_id', $this->comedor_id)
            ->when($this->id, function ($query) {
                return $query
                    ->where('id', '!=', $this->id);
            })
            ->get();

        foreach ($platos as $key => $plato) {
            $insumos2 = $plato->insumos;
            $diff = $insumos->diff($insumos2);
            $diff2 = $insumos2->diff($insumos);
            if ($diff->isEmpty() and $diff2->isEmpty()) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Ya existe un plato con esta combinacion de insumos';
    }
}
