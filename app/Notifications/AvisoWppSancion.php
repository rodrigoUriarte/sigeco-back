<?php

namespace App\Notifications;

use App\Channels\Messages\WhatsAppMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Channels\WhatsAppChannel;
use App\Models\Asistencia;
use App\Models\Sancion;
use App\Order;
use App\User;
use Carbon\Carbon;

class AvisoWppSancion extends Notification
{
    use Queueable;

    public $sancion;

    public function __construct(Sancion $sancion)
    {
        $this->sancion = $sancion;
    }

    public function via($notifiable)
    {
        return [WhatsAppChannel::class];
    }

    public function toWhatsApp($notifiable)
    {
        $comensal = $this->sancion->user;
        $comedor = $this->sancion->comedor;
        $regla = $this->sancion->regla;
        $fechas_inasitencias = $this->sancion->asistencias()->with('inscripcion')
            ->get()->pluck('inscripcion.fecha_inscripcion')->implode(',');
        $fecha_sancion = Carbon::parse($this->sancion->fecha)->format('d/m/Y');
        $operativo = User::whereHas('persona', function ($query) use ($comedor) {
            $query->where('comedor_id', $comedor->id);
        })
            ->whereHas('roles', function ($query) {
                $query->where('name', 'operativo');
            })
            ->inRandomOrder()
            ->first();

        return (new WhatsAppMessage)->content("Buenos dias {$comensal->name}, le informamos que se ha registrado una sancion a su nombre en el {$comedor->descripcion}, por las inasistencias de el/los siguiente/s dia/s {$fechas_inasitencias}.
Lo cual no cumple la siguiente regla del comedor: {$regla->descripcion}.
Se le ha sancionado el siguiente dia: {$fecha_sancion}.
Si considera que esta sancion no corresponde pongase en contacto con el administrador del {$comedor->descripcion}, {$operativo->name} al siguiente numero {$operativo->persona->telefono}.");
    }
}
