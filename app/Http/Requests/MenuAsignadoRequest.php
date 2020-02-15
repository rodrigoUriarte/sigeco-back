<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\MenuAsignado;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class MenuAsignadoRequest extends FormRequest
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
        $firstDayofNextMonth = Carbon::now()->addMonth()->startOfMonth()->toDateString();
        $lastDayofNextMonth = Carbon::now()->addMonth()->endOfMonth()->toDateString();

        $limma = backpack_user()->persona->comedor->parametro->limite_menu_asignado;
        if (Carbon::now()->daysInMonth < $limma) {
            $limma = Carbon::now()->daysInMonth - (Carbon::now()->daysInMonth - 1);
        }else {
            $limma = Carbon::now()->daysInMonth - $limma;
        }
        echo"";

        return [
            'user_id' => 'required',
            'menu_id' => 'required',
            //El menu asignado debe ser asignado por un mes entero. Desde el primer dia del mes hasta el ultimo dia de dicho mes
            //Ademas se debe agregar el menu asignado 15 dias antes del primer dia del mes a asignar.
            //El menu asignado solo se puede agregar para el mes siguiente.
            'fecha_inicio' => [
                'required',
                'bail',
                'date',
                'after:' . $limma . 'days',
                'date_equals:' . $firstDayofNextMonth,

                Rule::unique('menus_asignados')
                    ->where(function ($query) {
                        return $query
                            ->where('fecha_inicio', '=', $this->fecha_inicio)
                            ->where('user_id', '=', backpack_user()->id);
                    })
                    ->ignore($this->id),
            ],
            'fecha_fin' => [
                'required',
                'date',
                'date_equals:' . $lastDayofNextMonth
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
        return [];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        $firstDayofNextMonth = Carbon::now()->addMonth()->startOfMonth();
        $lastDayofNextMonth = Carbon::now()->addMonth()->endOfMonth();
        $lma = backpack_user()->persona->comedor->parametro->limite_menu_asignado;
        if (Carbon::now()->daysInMonth < $lma) {
            $lma = Carbon::now()->daysInMonth -1;
        }else {
            $lma = $lma - 1;
        }
        return [
            'fecha_inicio.after' => 'El menu asignado puede agregarse los primeros ' . $lma . ' dias del mes anterior a la fecha inicio seleccionada',
            'fecha_inicio.date_equals' => 'La fecha de inicio debe ser: ' . date_format($firstDayofNextMonth, 'd-m-Y'),
            'fecha_inicio.unique' => 'Ya tiene un menu asignado en las fechas seleccionadas',
            'fecha_fin.date_equals' => 'La fecha de fin debe ser: ' . date_format($lastDayofNextMonth, 'd-m-Y'),

        ];
    }
}
