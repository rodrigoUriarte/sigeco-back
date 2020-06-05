<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Backpack\CRUD\app\Models\Traits\CrudTrait; // <------------------------------- this one
use Spatie\Permission\Traits\HasRoles;// <---------------------- and this one

class User extends Authenticatable
{
    use Notifiable;
    use CrudTrait; // <----- this
    use HasRoles; // <------ and this

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'persona_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //RELACIONES
    public function persona()
    {
        return $this->belongsTo('App\Models\Persona');
    }
    public function inscripciones()
    {
        return $this->hasMany('App\Models\Inscripcion');
    }
    public function menusAsignados()
    {
        return $this->hasMany('App\Models\MenuAsignado');
    }
    public function sanciones()
    {
        return $this->hasMany('App\Models\Sancion');
    }
    public function diasPreferencia(){
        return $this->hasMany('App\Models\DiaPreferencia');
    }
}
