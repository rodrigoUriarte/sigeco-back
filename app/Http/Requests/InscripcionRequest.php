<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\BandaHoraria;
use App\Models\Inscripcion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use App\Models\MenuAsignado;
use Illuminate\Validation\Rule;

class InscripcionRequest extends FormRequest
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


            'menu_asignado_id' => [
                'bail',
                'required',
                Rule::exists('menus_asignados', 'id')
                    ->where(function ($query) {
                        $query
                            ->where('user_id', '=', backpack_user()->id)
                            ->whereDate('fecha_inicio', '<=', $this->fecha_inscripcion)
                            ->whereDate('fecha_fin', '>=', $this->fecha_inscripcion)
                            ->first();
                    }),
            ],

            'banda_horaria_id' => [
                'bail',
                'required',
                function ($attribute, $value, $fail) {

                    $max = BandaHoraria::find($value)->limite_comensales;

                    $cont = Inscripcion::where('banda_horaria_id', '=', $value)
                        ->where('fecha_inscripcion', '=', $this->fecha_inscripcion)
                        ->count();

                    if ($cont >= $max) {
                        $fail('No hay mas cupos para esta banda horaria');
                    }
                }
            ],

            'fecha_inscripcion' => [
                'bail',
                'required',
                'date',
                'after: 3 hours',
                // Rule::unique('inscripciones', 'fecha_inscripcion')->ignore(!(backpack_user()->id)),
                function ($attribute, $value, $fail) {

                    $cont = Inscripcion::where('fecha_inscripcion', '=', $value)
                        ->where('user_id', '=', backpack_user()->id)
                        ->count();

                    if ($cont >= 1) {
                        $fail('Ya existe una inscripcion con su usuario para este dia');
                    }
                }
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
            //
        ];
    }
}
