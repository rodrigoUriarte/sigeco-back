<?php

namespace App\Notifications;

use App\Channels\Messages\WhatsAppMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Channels\WhatsAppChannel;
use App\Models\Asistencia;
use App\Order;
use App\User;

class AvisoWppInasistencia extends Notification
{
  use Queueable;

  public $asistencia;
  
  public function __construct(Asistencia $asistencia)
  {
    $this->asistencia = $asistencia;
  }
  
  public function via($notifiable)
  {
    return [WhatsAppChannel::class];
  }
  
  public function toWhatsApp($notifiable)
  {
    $comensal = $this->asistencia->inscripcion->user;
    $comedor = $this->asistencia->comedor;
    $fecha_inscripcion = $this->asistencia->inscripcion->fecha_inscripcion;
    $operativo = User::whereHas('persona', function ($query) use($comedor) {
        $query->where('comedor_id', $comedor->id);
        })
        ->whereHas('roles', function ($query) {
            $query->where('name', 'operativo');
        })
        ->inRandomOrder()
        ->first();

    return (new WhatsAppMessage)
        ->content("Buenos dias {$comensal->name}, le informamos que se ha registrado una inasistencia a su nombre en el {$comedor->descripcion}, por una inscripcion del dia {$fecha_inscripcion}. Si considera que esta inasistencia no corresponde pongase en contacto con el administrador del {$comedor->descripcion}, {$operativo->name} al siguiente numero {$operativo->persona->telefono} .");
  }
}