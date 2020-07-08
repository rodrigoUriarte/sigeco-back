<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\BandaHoraria;
use App\Rules\BandaHorariaRule;
use App\Rules\HoraFinRule;
use App\Rules\HoraInicioRule;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BandaHorariaRequest extends FormRequest
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
            'hora_inicio' => Carbon::parse($this->hora_inicio)->format('H:i'),
            'hora_fin' => Carbon::parse($this->hora_fin)->format('H:i'),
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
            'comedor_id' => [Rule::exists('comedores', 'id')],
            'descripcion' => [
                'required',
                Rule::unique('bandas_horarias')
                    ->where(function ($query) {
                        return $query
                            ->where('comedor_id', '=', $this->comedor_id)
                            ->where('descripcion', '=', $this->descripcion);
                    })
                    ->ignore($this->id),
            ],

            'hora_inicio' => [
                'required', 'date_format:H:i',
                new HoraInicioRule($this->id, $this->comedor_id)
            ],

            'hora_fin' => [
                'required', 'date_format:H:i', 'after:hora_inicio',
                new HoraFinRule($this->id, $this->comedor_id, $this->hora_inicio)
            ],

            'limite_comensales' => ['required', 'integer'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            //controlo que la hora inicio y fin no abarquen una banda horaria existente
            $ha = BandaHoraria::where(function ($query) {
                return $query
                    ->when($this->id, function ($query) {
                        return $query
                            ->where('id', '!=', $this->id)
                            ->where('comedor_id', $this->comedor_id)
                            ->whereTime('hora_inicio', '>',  Carbon::parse($this->hora_inicio)->format('H:i:s'))
                            ->whereTime('hora_fin', '<',  Carbon::parse($this->hora_fin)->format('H:i:s'));
                    }, function ($query) {
                        return $query
                            ->where('comedor_id', $this->comedor_id)
                            ->whereTime('hora_inicio', '>',  Carbon::parse($this->hora_inicio)->format('H:i:s'))
                            ->whereTime('hora_fin', '<',  Carbon::parse($this->hora_fin)->format('H:i:s'));
                    });
            })->count();

            if ($ha !== 0) {
                $validator->errors()->add('hora_inicio', 'La hora inicio abarca una banda horaria existente');
                $validator->errors()->add('hora_fin', 'La hora fin abarca una banda horaria existente');
            }
        });
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
