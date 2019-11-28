<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Comedor extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'comedores';
    protected $primaryKey = 'id';
    public $timestamps = true;
    // protected $guarded = [];
    protected $fillable = ['descripcion','direccion','unidad_academica_id'];
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
    public function personas(){
        return $this->hasMany('App\Models\Persona');
    }
    public function unidadAcademica(){
        return $this->belongsTo('App\Models\UnidadAcademica');
    }
    public function insumos(){
        return $this->hasMany('App\Models\Insumo');
    }
    public function menus(){
        return $this->hasMany('App\Models\Menu');
    }
    public function platos(){
        return $this->hasMany('App\Models\Plato');
    }
    public function bandasHorarias(){
        return $this->hasMany('App\Models\BandaHoraria');
    }
    public function insumosPlatos(){
        return $this->hasMany('App\Models\InsumoPlato');
    }
    public function insripciones(){
        return $this->hasMany('App\Models\Inscripcion');
    }
    public function menusAsignados(){
        return $this->hasMany('App\Models\MenuAsignado');
    }
    public function ingresosInsumos(){
        return $this->hasMany('App\Models\IngresoInsumo');
    }
    public function platosAsignados(){
        return $this->hasMany('App\Models\PlatoAsignado');
    }
    public function lotes(){
        return $this->hasMany('App\Models\Lote');
    }
    public function asistencias(){
        return $this->hasMany('App\Models\Asistencia');
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
