<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\MenuAsignado;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

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
        $ls = Carbon::now()->addMonth()->lastOfMonth();
        $fi = Carbon::createFromDate($this->fecha_inicio);
        $pd = Carbon::parse($fi)->firstOfMonth();
        $ff = Carbon::parse($pd)->addMonth(); //->subSecond();
        return [
            'user_id' => 'required',
            'menu_id' => 'required',
            //El menu asignado debe ser asignado por un mes entero. Desde el primer dia del mes hasta el primer dia del mes siguiente.
            //Ademas se debe agregar el menu asignado 15 dias antes del primer dia del mes a asignar.
            //La fecha de inicio y la fecha fin deben ser obligatoriamente el primer dia de un mes
            //El menu asignado solo se puede agregar para el mes siguiente.
            'fecha_inicio' => [
                'required', 'date', 'after: 1 days', 'before:' . $ls ,'date_equals:' . $pd ,

                    function ($attribute, $value, $fail) {

                        $existe = MenuAsignado::where('fecha_inicio', '=', $value)
                            ->where('user_id', '=', backpack_user()->id)
                            ->count();

                        if ($existe >= 1) {
                            $fail('Ya existe un menu asignado en esta fecha');
                        }
                    },
            ],


            'fecha_fin' => 'required|date|date_equals:' . $ff,
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
