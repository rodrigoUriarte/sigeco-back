<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;

class Persona extends Model implements Auditable
{
    use CrudTrait;
    use Notifiable;
    use \OwenIt\Auditing\Auditable;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'personas';
    protected $primaryKey = 'id';
    public $timestamps = true;
    // protected $guarded = ['id'];
    protected $fillable = ['dni', 'nombre', 'apellido', 'telefono', 'email', 'unidad_academica_id', 'comedor_id'];
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
    public function user()
    {
        return $this->hasOne('App\User');
    }
    public function unidadAcademica()
    {
        return $this->belongsTo('App\Models\UnidadAcademica');
    }
    public function comedor()
    {
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

    public function getNombreUsuarioAttribute()
    {
        $nombre=str_replace(" ","",$this->nombre);
        $apellido=str_replace(" ","",$this->apellido);
        return "{$nombre}{$apellido}";
    }
    public function routeNotificationForWhatsApp()
    {
        return $this->telefono;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
