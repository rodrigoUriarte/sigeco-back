<?php

namespace App\Rules;

use App\Models\BandaHoraria;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class HoraInicioRule implements Rule
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
        //controlo que la hora fin no este dentro de una banda horaria existente
        $hi = BandaHoraria::where(function ($query) use ($value) {
            return $query
                ->when($this->id, function ($query) use ($value) {
                    return $query
                        ->where('id', '!=', $this->id)
                        ->where('comedor_id', $this->comedor_id)
                        ->whereTime('hora_inicio', '<',  Carbon::parse($value)->format('H:i:s'))
                        ->whereTime('hora_fin', '>',  Carbon::parse($value)->format('H:i:s'));
                }, function ($query) use ($value) {
                    return $query
                        ->where('comedor_id', $this->comedor_id)
                        ->whereTime('hora_inicio', '<',  Carbon::parse($value)->format('H:i:s'))
                        ->whereTime('hora_fin', '>',  Carbon::parse($value)->format('H:i:s'));
                });
        })->count();

        if ($hi === 0) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'La :attribute coincide con una banda horaria existente.';
    }
}
