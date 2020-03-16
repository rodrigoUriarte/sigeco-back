<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DiaServicioRequest extends FormRequest
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
            'comedor_id' => ['required', Rule::exists('comedores', 'id')],
            'dia' => [
                'required',
                Rule::unique('dias_servicio')
                    ->where(function ($query) {
                        return $query
                            ->where('comedor_id', '=', $this->comedor_id);
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
            'dia.unique' => 'Ya se encuentra cargado ese dia de servicio para este comedor.'
        ];
    }
}
