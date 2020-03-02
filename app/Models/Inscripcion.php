<?php

namespace App\Models;

use App\Support\FormatDate\FormatsDates;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

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
    protected $fillable = ['fecha_inscripcion', 'retira', 'user_id', 'banda_horaria_id', 'menu_asignado_id', 'comedor_id'];

    // protected $hidden = [];


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
        return $this->belongsTo('App\Models\BackpackUser');
    }
    public function bandaHoraria()
    {
        return $this->belongsTo('App\Models\BandaHoraria');
    }
    public function menuAsignado()
    {
        return $this->belongsTo('App\Models\MenuAsignado');
    }
    public function comedor()
    {
        return $this->belongsTo('App\Models\Comedor');
    }
    public function asistencia()
    {
        return $this->hasOne('App\Models\Asistencia');
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
    public function getFechaInscripcionFormatoAttribute()
    {
        $myDate = Date::createFromFormat('Y-m-d', $this->fecha_inscripcion);
        return date_format($myDate,'d-m-Y');
    }
    public function getNombreAttribute(){
        return $this->user->name; 
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

}
