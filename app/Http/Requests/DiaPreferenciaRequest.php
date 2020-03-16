<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\DiaPreferencia;
use App\Models\Inscripcion;
use App\Models\Sancion;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DiaPreferenciaRequest extends FormRequest
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
        $limins = Carbon::createFromTimeString(backpack_user()->persona->comedor->parametro->limite_inscripcion);
        $aux = $limins->diffInMinutes(Carbon::tomorrow());
        return [
            'user_id' => ['required', Rule::exists('users', 'id')],

            'dia_servicio_id' => [
                'required',
                Rule::unique('dias_preferencia')
                    ->where(function ($query) {
                        return $query
                            ->where('user_id', '=', $this->user_id);
                    })
                    ->ignore($this->id),
            ],

            'banda_horaria_id' => [
                'bail',
                'required',
                //ESTA REGLA PRESENTA EL PRBLEMA QUE SI LA BANDA HORARIA COMPLETA SU CUPO,
                //EL COMENSAL AL EDITAR LA INSCRIPCION NO PODRA SELECCIONAR DICHA BANDA HORARIA QUE YA ESTA LLENA.
                //SE PODRIA CORREGIR ESTO?
                Rule::exists('bandas_horarias', 'id')
                    ->where(function ($query) {
                        return $query
                            ->where(
                                'limite_comensales',
                                '>',
                                DiaPreferencia::whereHas('user.persona', function ($query) {
                                    $query->where('comedor_id', backpack_user()->persona->comedor_id);
                                })
                                    ->where('banda_horaria_id', '=', $this->banda_horaria_id)
                                    ->where('dia_servicio_id', '=', $this->dia_servicio_id)
                                    ->where('retira', false)
                                    ->count()
                            );
                    }),
            ],

            'retira' => ['required', 'boolean']
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
            'banda_horaria_id.exists' => 'Esta banda horaria se encuentra llena, seleccione otra',
            'dia_servicio_id.unique' => 'Ya tiene una preferencia cargada para este dia'
        ];
    }
}
