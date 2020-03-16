<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

class Asistencia extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'asistencias';
    protected $primaryKey = 'id';
    public $timestamps = true;
    // protected $guarded = ['id'];
    protected $fillable = ['fecha_asistencia','asistio','asistencia_fbh','inscripcion_id','comedor_id','sancion_id'];
    // protected $hidden = [];
    // protected $dates = [];
    protected $appends = ['comensal'];

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
    public function inscripcion(){
        return $this->belongsTo('App\Models\Inscripcion');
    }
    public function comedor(){
        return $this->belongsTo('App\Models\Comedor');
    }
    public function sancion(){
        return $this->belongsTo('App\Models\Sancion');
    }
    public function justificacion(){
        return $this->hasOne('App\Models\Justificacion');
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
    public function getComensalAttribute(){
        return $this->inscripcion->user->name; 
    }
    
    public function getFechaInscripcionFormatoAttribute()
    {
        $myDate = Date::createFromFormat('Y-m-d', $this->inscripcion->fecha_inscripcion);
        return date_format($myDate,'d-m-Y');
    }


    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
