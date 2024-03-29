<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use OwenIt\Auditing\Contracts\Auditable;

class Lote extends Model implements Auditable
{
    use CrudTrait;
    use \OwenIt\Auditing\Auditable;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'lotes';
    protected $primaryKey = 'id';
    public $timestamps = true;
    // protected $guarded = ['id'];
    protected $fillable = ['fecha_vencimiento','cantidad','usado','comedor_id','insumo_id','insumo_remito_id'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function insumoRemito(){
        return $this->belongsTo('App\Models\InsumoRemito');
    }
    public function comedor(){
        return $this->belongsTo('App\Models\Comedor');
    }
    public function insumo(){
        return $this->belongsTo('App\Models\Insumo');
    }
    public function platosAsignados(){
        return $this->belongsToMany('App\Models\PlatoAsignado','lote_plato_asignado','lote_id','plato_asignado_id')->withPivot('cantidad')->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getFechaVencimientoFormatoAttribute()
    {
        $myDate = Date::createFromFormat('Y-m-d', $this->fecha_vencimiento);
        return date_format($myDate,'d-m-Y');
    }

    public function getCantidadUMAttribute()
    {
        return "{$this->cantidad}  ({$this->insumo->unidad_medida})";
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
