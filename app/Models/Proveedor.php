<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'proveedores';
    protected $primaryKey = 'id';
    public $timestamps = true;
    //protected $guarded = ['id'];
    protected $fillable = ['cuit', 'nombre', 'telefono', 'email', 'direccion'];
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
    public function comedores()
    {
        return $this->belongsToMany('App\Models\Comedor', 'comedor_proveedor', 'proveedor_id', 'comedor_id');
    }
    public function remitos()
    {
        return $this->hasMany('App\Models\Remito');
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
    public function getCuitMaskedAttribute()
    {
        $cuit=$this->cuit;
        $masked = sprintf(
            "%s-%s-%s",
            substr($cuit, 0, 2),
            substr($cuit, 2, 8),
            substr($cuit, 10, 1)
        );
        return $masked;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setCuitAttribute($value)
    {
        $this->attributes['cuit'] = str_replace("-", "", $value);
    }
}
