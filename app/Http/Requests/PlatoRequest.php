<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class PlatoRequest extends FormRequest
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
            'insumos' => json_decode($this->insumos, true),
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
            'comedor_id' => ['required', Rule::exists('comedores', 'id')],
            'menu_id' => ['required', Rule::exists('menus', 'id')],
            'descripcion' => [
                'required',
                Rule::unique('platos')
                    ->where(function ($query) {
                        return $query
                            ->where('comedor_id', '=', $this->comedor_id)
                            ->where('descripcion', '=', $this->descripcion);
                    })
                    ->ignore($this->id),
            ],
            'insumos.*.insumos' => ['required','distinct'],
            'insumos.*.cantidad' => ['required'],
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
            //'insumos.*.insumos' => 'insumo',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        $messages = [];
        foreach ($this->insumos as $index => $requestData) {
            foreach ($requestData as $input => $value) {
                $messages['insumos.' . $index . '.insumos' . '.required'] = 'El insumo numero ' . ($index + 1)  . '  de la preparacion del plato esta vacio';
                $messages['insumos.' . $index . '.insumos' . '.distinct'] = 'El insumo numero ' . ($index + 1)  . '  de la preparacion del plato esta repetido';
                $messages['insumos.' . $index . '.cantidad' . '.required'] = 'El insumo numero ' . ($index + 1)  . '  de la preparacion no tiene especificada una cantidad';
            }
        }
        return $messages;       
    }
}
