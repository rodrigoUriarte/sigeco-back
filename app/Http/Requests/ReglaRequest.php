<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReglaRequest extends FormRequest
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
            'comedor_id' => [Rule::exists('comedores', 'id'),
            Rule::unique('reglas')
            ->where(function ($query) {
                return $query
                    ->where('comedor_id', '=', $this->comedor_id)
                    ->where('cantidad_faltas', '=', $this->cantidad_faltas)
                    ->where('tiempo', '=', $this->tiempo)
                    ->where('dias_sancion', '=', $this->dias_sancion);

            })
            ->ignore($this->id),],
            'descripcion' => ['required','string'],
            'tiempo' => ['required','string'],
            'cantidad_faltas' => ['required','integer','min:1'],
            'dias_sancion' => ['required','integer','min:1'],
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
            'comedor_id.unique' => "Ya existe una regla con estos parametros para este comedor"
        ];
    }
}
