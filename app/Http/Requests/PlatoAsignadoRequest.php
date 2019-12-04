<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
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
        return [
            'comedor_id' => [Rule::exists('comedores','id')],
            'menu_id' => ['required', Rule::exists('menus', 'id')],
            'plato_id' => ['required', Rule::exists('platos', 'id')],
            'fecha' => [
                'required',
                'date',
                //'before: 3 hours',
                Rule::unique('platos_asignados')
                    ->where(function ($query) {
                        return $query
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
        return [
            //
        ];
    }
}
