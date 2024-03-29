<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProveedorRequest extends FormRequest
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
            'cuit' => str_replace("-", "", $this->cuit),
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
            'cuit' => ['required', 'digits:11', Rule::unique('proveedores')->ignore($this->id)],
            'email' => ['required', 'email:rfc,dns', Rule::unique('proveedores')->ignore($this->id)],
            'nombre' => ['required', Rule::unique('proveedores')->ignore($this->id)],
            'telefono' => ['required', 'phone:AR', Rule::unique('proveedores')->ignore($this->id)],
            'direccion' => ['required'],
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
