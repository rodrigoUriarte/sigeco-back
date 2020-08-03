<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'insumos';
    protected $primaryKey = 'id';
    public $timestamps = true;
    // protected $guarded = ['id'];
    protected $fillable = ['comedor_id', 'descripcion', 'unidad_medida'];
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

    public function comedor()
    {
        return $this->belongsTo('App\Models\Comedor');
    }
    public function lotes()
    {
        return $this->hasMany('App\Models\Lote');
    }
    public function remitos()
    {
        return $this->belongsToMany('App\Models\Remito')->using('App\Models\InsumoRemito')->withPivot(['id','cantidad','fecha_vencimiento'])->withTimestamps();
    }
    public function platos(){
        return $this->belongsToMany('App\Models\Plato')->withPivot(['cantidad']);
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
    public function getDescripcionUMAttribute()
    {
        return "{$this->descripcion}  ({$this->unidad_medida})";
    }

    public function getDescripcionCantidadVencimientoAttribute()
    {
        return "{$this->descripcion}  ({$this->pivot->cantidad} {$this->unidad_medida}) - F.Venc: {$this->pivot->fecha_vencimiento}";
    }

    public function getDescripcionCantidadAttribute()
    {
        return "{$this->descripcion}  ({$this->pivot->cantidad} {$this->unidad_medida})";
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setDescripcionAttribute($value)
    {
        $this->attributes['descripcion'] = strtoupper($value);
    }
    public function setUnidadMedidaAttribute($value)
    {
        $this->attributes['unidad_medida'] = strtoupper($value);
    }
}
