<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class InsumoPlatoRequest extends FormRequest
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
            'insumo_id' => ['required', Rule::exists('insumos', 'id')],
            'plato_id' => ['required', Rule::exists('platos', 'id')],
            'cantidad' => ['required','numeric','min:0.01','regex:/^\d+(\.\d{1,2})?$/'],
            'comedor_id' => [
                Rule::unique('insumo_plato')
                    ->where(function ($query) {
                        return $query
                            ->where('comedor_id', '=', $this->comedor_id)
                            ->where('insumo_id', '=', $this->insumo_id)
                            ->where('plato_id', '=', $this->plato_id);
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
