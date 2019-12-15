<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\BandaHoraria;
use App\Models\Inscripcion;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AsistenciaRequest extends FormRequest
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
            'comedor_id' => [Rule::exists('comedores', 'id')],
            'inscripcion_id' => [
                'required',
                Rule::unique('asistencias')->ignore($this->id),

                function ($attribute, $value, $fail) {
                    $banda_permitida = Inscripcion::where('id', '=', $value)
                        ->whereHas('bandaHoraria', function (Builder $query) {
                            $query->where('hora_inicio', '<=', Carbon::now()->toTimeString())
                                ->where('hora_fin', '>=', Carbon::now()->toTimeString());
                        })
                        ->get();

                    if ($banda_permitida->isEmpty()) {
                        $fail('Fuera de banda horaria');
                    }
                },
                function ($attribute, $value, $fail) {
                    
                    $ultima_banda = BandaHoraria::where('comedor_id', backpack_user()->persona->comedor_id)
                        ->orderByDesc('hora_fin')
                        ->first();

                    $flag = false;

                    if ($ultima_banda->hora_inicio <= Carbon::now()->toTimeString() && $ultima_banda->hora_fin >= Carbon::now()->toTimeString()) {
                        $flag = true;
                    }

                    if ($flag == false) {
                        $fail('Fuera de ultima banda horaria');
                    }
                },
            ],
            'asistio' => ['boolean'],
            'fecha_asistencia' => ['date'],
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
