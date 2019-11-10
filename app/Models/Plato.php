<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Plato extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'platos';
    protected $primaryKey = 'id';
    public $timestamps = true;
    // protected $guarded = ['id'];
    protected $fillable = ['menu_id','descripcion','comedor_id'];
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
    public function menu(){
        return $this->belongsTo('App\Models\Menu');
    }
    // public function insumos(){
    //     return $this->belongsToMany('App\Models\Insumo')->using('App\Models\InsumoPlato')->withPivot(['cantidad']);
    // }
    //QUEDA PENDIENTE EL USO COMO TABLA PIVOT HASTA QUE BACKPACK SOPORTE CAMPOS APARTE DE LOS FK EN LA TABLA PIVOT
    public function insumo_plato(){
        return $this->hasMany('App\Models\InsumoPlato');
    }
    public function comedor(){
        return $this->belongsTo('App\Models\Comedor');
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

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setDescripcionAttribute($value)
    {
        $this->attributes['descripcion'] = strtoupper($value);
    }
}
