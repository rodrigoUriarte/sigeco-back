<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class MenuAsignado extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'menus_asignados';
    protected $primaryKey = 'id';
    public $timestamps = true;
    // protected $guarded = ['id'];
    protected $fillable = ['user_id', 'menu_id', 'fecha_inicio', 'fecha_fin','comedor_id'];
    // protected $dates = ['fecha_inicio','fecha_fin'];
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
        return $this->belongsTo('App\Models\BackpackUser');
    }
    public function menu()
    {
        return $this->belongsTo('App\Models\Menu');
    }
    public function inscripciones()
    {
        return $this->hasMany('App\Models\Inscripcion');
    }
    public function comedor(){
        return $this->belongsTo('App\Models\Comedor');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    // public function scopeDentroRango($query,$fecha){
    //     return $query
    //     ->where('user_id','=',backpack_user()->id)
    //     ->whereDate('$this->fecha_inicio','<=',$fecha)
    //     ->whereDate('$this->fecha_fin','>=',$fecha);
    // }
    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getRangoFechasAttribute(){
        return "{$this->fecha_inicio} / {$this->fecha_fin}";
    }

    public function getDescripcionMenuAttribute(){
        return $this->menu->descripcion; 
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
