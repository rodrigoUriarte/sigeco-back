<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IngresoInsumoRequest extends FormRequest
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
            'user_id' => [Rule::exists('users', 'id')],
            'comedor_id' => [Rule::exists('comedores','id')],
            'cantidad' => ['required','numeric','min:1'],
            'fecha_vencimiento' => 'required|date|after:yesterday',
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
