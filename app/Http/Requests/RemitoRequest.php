<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RemitoRequest extends FormRequest
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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'numero' => str_replace("-", "", $this->numero),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'numero' => ['required', 'digits:13'],
            'proveedor_id' => ['required', Rule::exists('proveedores', 'id')],
            'insumo' => ['required'],
            'cantidad' => ['required'],
            'fecha_vencimiento' => ['required'],
            'insumo.*' => ['required', Rule::exists('insumos', 'id')],
            'cantidad.*' =>  ['required', 'numeric', 'min:0.01', 'max:999999.99', 'regex:/^\d+(\.\d{1,2})?$/'],
            'fecha_vencimiento.*' =>  ['required', 'date', 'after:now'],
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
        // $messages = [];
        // foreach ($this->fecha_vencimiento as $index => $value) {
        //     $messages['fecha_vencimiento.' . $index . '.after:now'] = 'falla';
        // }
        // return $messages;

        return [
            'fecha_vencimiento.*.after' =>  'El atributo :attribute debe tener una fecha posterior a hoy',
        ];
    }
}
