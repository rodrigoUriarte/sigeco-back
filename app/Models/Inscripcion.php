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
    protected $fillable = ['user_id','banda_horaria_id','menu_asignado_id','fecha_inscripcion','fecha_asistencia'];
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
    public function banda_horaria(){
        return $this->belongsTo('App\Models\BandaHoraria');
    }
    public function menu_asignado(){
        return $this->belongsTo('App\Models\MenuAsignado');
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
    

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
