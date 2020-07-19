<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Remito extends Model implements Auditable
{
    use CrudTrait;
    use \OwenIt\Auditing\Auditable;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'remitos';
    protected $primaryKey = 'id';
    public $timestamps = true;
    // protected $guarded = ['id'];
    protected $fillable = ['numero', 'fecha', 'proveedor_id', 'comedor_id'];
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
    public function proveedor()
    {
        return $this->belongsTo('App\Models\Proveedor');
    }
    public function comedor()
    {
        return $this->belongsTo('App\Models\Comedor');
    }
    public function insumos()
    {
        return $this->belongsToMany('App\Models\Insumo')->using('App\Models\InsumoRemito')->withPivot(['id','cantidad', 'fecha_vencimiento'])->withTimestamps();
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
    public function getNumeroMaskedAttribute()
    {
        $cuit=$this->numero;
        $masked = sprintf(
            "%s-%s",
            substr($cuit, 0, 5),
            substr($cuit, 5, 8)
        );
        return $masked;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setNumeroAttribute($value)
    {
        $this->attributes['numero'] = str_replace("-", "", $value);
    }
}
