<?php

namespace App\Models;

use App\User;
use Illuminate\Notifications\Notifiable;
use Backpack\CRUD\app\Models\Traits\InheritsRelationsFromParentModel;
use Backpack\CRUD\app\Notifications\ResetPasswordNotification as ResetPasswordNotification;

class BackpackUser extends User
{
    use InheritsRelationsFromParentModel;
    use Notifiable;

    protected $table = 'users';

    /**
     * Send the password reset notification.
     *
     * @param string $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }
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
    public function ingresosInsumos()
    {
        return $this->hasMany('App\Models\IngresoInsumo');
    }

}
