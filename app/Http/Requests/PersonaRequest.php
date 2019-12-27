<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Backpack\PermissionManager\app\Http\Requests\UserStoreCrudRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class PersonaRequest extends FormRequest
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
            'dni' => ['required','digits:8',Rule::unique('personas')->ignore($this->id)],
            'nombre' => 'required',
            'apellido' => 'required',
            'telefono' => ['required','digits:10',Rule::unique('personas')->ignore($this->id)],
            'email' => ['required','email:rfc,dns',Rule::unique('personas')->ignore($this->id)],
            'unidad_academica_id' => 'required',
            'comedor_id' => 'required',
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
