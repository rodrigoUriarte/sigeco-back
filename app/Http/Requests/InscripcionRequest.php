<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\BandaHoraria;
use App\Models\Inscripcion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use App\Models\MenuAsignado;
use App\Models\Sancion;
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
        $limins = Carbon::createFromTimeString(backpack_user()->persona->comedor->parametro->limite_inscripcion);
        $aux = $limins->diffInMinutes(Carbon::tomorrow());

        return [

            'user_id' => ['required', Rule::exists('users', 'id')],

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
                                Inscripcion::where('banda_horaria_id', '=', $this->banda_horaria_id)
                                    ->where('fecha_inscripcion', '=', $this->fecha_inscripcion)
                                    ->where('retira',false)
                                    ->count()
                            );
                    }),
            ],

            'menu_asignado_id' => [
                'bail',
                'required',
                Rule::exists('menus_asignados', 'id')
                    ->where(function ($query) {
                        return $query
                            ->where('user_id', '=', backpack_user()->id)
                            ->whereDate('fecha_inicio', '<=', $this->fecha_inscripcion)
                            ->whereDate('fecha_fin', '>=', $this->fecha_inscripcion)
                            ->first();
                    }),
            ],

            'fecha_inscripcion' => [
                'bail',
                'required',
                'date',
                //PARAMETRIZAR LOS DATOS DE ESTE REQUEST Y DEPUES REPLICAR LO HECHO EN INSCRIPCION(CONTROLLER OBSERVER Y REQUEST) PARA MENU ASIGANDO Y PLATO ASIGNADO
                'after:'.$aux.' minutes',
                Rule::unique('inscripciones')
                    ->where(function ($query) {
                        return $query
                            ->where('fecha_inscripcion', '=', $this->fecha_inscripcion)
                            ->where('user_id', '=', backpack_user()->id);
                    })
                    ->ignore($this->id),

                function ($attribute, $value, $fail) {
                    $existe_sancion = Sancion::where('comedor_id', '=', $this->comedor_id)
                        ->where('user_id', '=', $this->user_id)
                        ->where('desde', '<=', $value)
                        ->where('hasta', '>=', $value)
                        ->count();
                    if ($existe_sancion > 0) {
                        $fail('Tiene una sancion vigente para la fecha seleccionada');
                    }
                },
            ],

            'comedor_id' => [Rule::exists('comedores', 'id')],

            'retira'=> ['required', 'boolean']
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
            'banda_horaria_id.exists' => 'No hay cupo en esta banda horaria, seleccione otra',
            'menu_asignado_id.exists' => 'El menu asignado no coincide con la fecha de inscripcion, o no posee un menu asignado para dicha fecha',
            'fecha_inscripcion.unique' => 'Ya tiene una inscripcion registrada en esta fecha',
            'fecha_inscripcion.after' => 'La inscripcion para esta fecha se encuentra cerrada.'
        ];
    }
}
