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
    public function unidad_academica(){
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
    public function Inscripciones(){
        return $this->hasMany('App\Models\Inscripcion');
    }
    public function MenusAsignados(){
        return $this->hasMany('App\Models\MenuAsignado');
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
