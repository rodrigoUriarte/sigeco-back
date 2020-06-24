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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id=$this->id;
        $fecha=$this->fecha_vencimiento;
        $asd=null;
        return [
            'numero' => ['required', 'integer', 'digits:12'],
            'proveedor_id' => ['required', Rule::exists('proveedores', 'id')],
            'insumo' => ['required'],
            'cantidad' => ['required'],
            'fecha_vencimiento' => ['required'],
            'insumo.*' => ['required', Rule::exists('insumos', 'id')],
            'cantidad.*' =>  ['required', 'numeric', 'min:0.01', 'regex:/^\d+(\.\d{1,2})?$/'],
            'fecha_vencimiento.*' =>  ['required', 'date'],
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
