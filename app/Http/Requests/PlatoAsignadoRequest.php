<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlatoAsignadoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $limins = Carbon::createFromTimeString(backpack_user()->persona->comedor->parametro->limite_inscripcion);
        $aux = $limins->diffInMinutes(Carbon::tomorrow());

        return [
            'comedor_id' => [Rule::exists('comedores', 'id')],
            'menu_id' => ['required', Rule::exists('menus', 'id')],
            'plato_id' => ['required', Rule::exists('platos', 'id')],
            'fecha' => [
                'required',
                'date',
                'before:'.$aux.' minutes',
                Rule::unique('platos_asignados')
                    ->where(function ($query) {
                        return $query
                            ->where('comedor_id', '=', $this->comedor_id)
                            ->where('fecha', '=', $this->fecha)
                            ->where('menu_id', '=', $this->menu_id);
                    })
                    ->ignore($this->id),
            ],
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        $limins = Carbon::createFromTimeString(backpack_user()->persona->comedor->parametro->limite_inscripcion);

        return [

            'fecha.before' => 'Debe esperar a la hora limite ('.$limins->format('H:i').') de inscripciones para agregar un plato asignado.',
            'fecha.unique' => 'Ya existe un plato asignado para dicho menu en la fecha seleccionada.'

        ];
    }
}
