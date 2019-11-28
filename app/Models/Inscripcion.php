<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'inscripciones';
    protected $primaryKey = 'id';
    public $timestamps = true;
    // protected $guarded = ['id'];
    protected $fillable = ['user_id','banda_horaria_id','menu_asignado_id','fecha_inscripcion','fecha_asistencia','comedor_id'];
    //protected $dates = ['fecha_incripcion','fecha_asistencia'];

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
    public function user(){
        return $this->belongsTo('App\Models\BackpackUser');
    }
    public function bandaHoraria(){
        return $this->belongsTo('App\Models\BandaHoraria');
    }
    public function menuAsignado(){
        return $this->belongsTo('App\Models\MenuAsignado');
    }
    public function comedor(){
        return $this->belongsTo('App\Models\Comedor');
    }
    public function asistencia(){
        return $this->hasOne('App\Models\Asistencia');
    }
    // public function menuAsignadoMenu(){
    //     return $this->hasOneThrough('App\Models\Menu', 'App\Models\MenuAsignado','menu_id','id');
    // }

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
    public function getNombreInscripcionAttribute(){
        return $this->user->persona->nombre.' '.$this->user->persona->apellido; 
    }
    

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
