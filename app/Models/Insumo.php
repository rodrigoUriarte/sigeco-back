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

    public function comedor(){
        return $this->belongsTo('App\Models\Comedor');
    }

    // public function platos(){
    //     return $this->belongsToMany('App\Models\Plato')->using('App\Models\InsumoPlato')->withPivot(['cantidad']);
    // }
    //QUEDA PENDIENTE EL USO COMO TABLA PIVOT HASTA QUE BACKPACK SOPORTE CAMPOS APARTE DE LOS FK EN LA TABLA PIVOT
    
    public function insumosPlatos() {
        return $this->hasMany('App\Models\InsumoPlato');
    }
    public function ingresosInsumos(){
        return $this->hasMany('App\Models\IngresoInsumo');
    }
    public function lotes(){
        return $this->hasMany('App\Models\Lote');
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
