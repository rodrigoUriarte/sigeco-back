<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\BandaHoraria;
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $hi = $this->hora_inicio;
        $hf = $this->hora_fin;
        $comedor = $this->comedor_id;
        $bh = BandaHoraria::where('comedor_id', '=', $this->comedor_id)
            ->whereTime('hora_inicio',  Carbon::parse($this->hora_inicio)->format('H:i:s'))
            ->whereTime('hora_fin',  Carbon::parse($this->hora_fin)->format('H:i:s'))
            ->get();
        $hx = BandaHoraria::where(function ($query) {
            return $query
                ->where('comedor_id', '=', $this->comedor_id)
                ->whereTime('hora_inicio', '<=',  Carbon::parse($this->hora_inicio)->format('H:i:s'))
                ->whereTime('hora_fin', '>=',  Carbon::parse($this->hora_inicio)->format('H:i:s'));
        })
            ->get();

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

            // 'hora_inicio' => [
            //     'required',
            //     function ($attribute, $value, $fail) {
            //         $bhs = BandaHoraria::where('comedor_id', '=', $this->comedor_id)
            //             ->where('hora_inicio', '<=', $this->hora_inicio)
            //             ->where('hora_fin', '>=', $this->hora_inicio)
            //             ->get();

            //         foreach ($bhs as $bh) {
            //             if ($bh->hora_inicio <= $value && $bh->hora_fin >= $value) {
            //                 $fail('La hora inicio establecida ya se encuentra ocupada por la banda horaria: ' . $bh->descripcion);
            //             }
            //         }
            //     },
            // ],

            'hora_inicio' => [
                'required',
                Rule::unique('bandas_horarias')
                     ->where(function ($query) {
                         return $query
                            ->where('comedor_id', '=', $this->comedor_id)
                            ->whereTime('hora_inicio', '=',  Carbon::parse($this->hora_inicio)->format('H:i:s'))
                            ->whereTime('hora_fin', '=',  Carbon::parse($this->hora_fin)->format('H:i:s'));
                    })
                    ->ignore($this->id),
            ],
            //SI NO ENCUENTRO LA SOLUCION PROBAR MEDIANTE OBSERVERS

            // 'hora_fin' => [
            //     'required', 'after:hora_inicio',
            //     function ($attribute, $value, $fail) {
            //         $bhs = BandaHoraria::where('comedor_id', '=', $this->comedor_id)
            //             ->get();

            //         foreach ($bhs as $bh) {
            //             if ($bh->hora_inicio <= $value && $bh->hora_fin >= $value) {
            //                 $fail('La hora fin establecida ya se encuentra ocupada por la banda horaria: ' . $bh->descripcion);
            //             }
            //         }
            //     },

            //     function ($attribute, $value, $fail) {
            //         $bhs = BandaHoraria::where('comedor_id', '=', $this->comedor_id)
            //             ->orderBy('hora_fin', 'desc')
            //             ->get();

            //         foreach ($bhs as $bh) {
            //             if ($bh->hora_inicio >= $this->hora_inicio && $bh->hora_fin <= $value) {
            //                 $fail('Las horas fin y hora inicio no pueden ocupar el tiempo de otras bandas horarias: ' . $bh->descripcion);
            //             }
            //         }
            //     },
            // ],

            'hora_fin' => [
                'required', 'after:hora_inicio',
                Rule::unique('bandas_horarias')
                    ->where(function ($query) {
                        return $query
                            ->where('comedor_id', '=', $this->comedor_id)
                            ->whereTime('hora_inicio', '<=',  Carbon::parse($this->hora_inicio)->format('H:i:s'))
                            ->whereTime('hora_fin', '>=',  Carbon::parse($this->hora_fin)->format('H:i:s'));
                    })
                    ->ignore($this->id),
            ],

            'limite_comensales' => ['required', 'integer'],
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
            'hora_inicio.unique' => 'La hora inicio establecida ya se encuentra ocupada por una banda horaria',
            'hora_fin.unique' => 'La hora fin establecida ya se encuentra ocupada por una banda horaria',
        ];
    }
}
