<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

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
        $menu = \App\Models\MenuAsignado::where('user_id', backpack_user()->id)
        ->whereDate('fecha_inicio', '<=', $this->fecha_inscripcion)
        ->whereDate('fecha_fin', '>=', $this->fecha_inscripcion)
        ->first();
        $fi = $menu->fecha_inicio;
        $ff = $menu->fecha_fin;

        return [
            'fecha_inscripcion' => 'required|date|after_or_equal:' . $fi . '|before_or_equal:' . $ff . '|after: 3 hours',
            'banda_horaria_id' => 'required',
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
