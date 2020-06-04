<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;


class InsumoRemito extends Pivot
{
    public $incrementing = true;


    public function lote()
    {
        return $this->hasOne('App\Models\Lote','insumo_remito_id');
    }
}
