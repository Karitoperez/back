<?php

namespace App\Listeners;

use App\Events\EnviarMensaje;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Pusher\Pusher;

class EnviarMensajeListener
{
    protected $pusher;

    /**
     * Create the event listener.
     */
    public function __construct(Pusher $pusher)
    {
        $this->pusher = $pusher;
    }

    /**
     * Handle the event.
     */
    public function handle(EnviarMensaje $event)
    {
        // Aquí puedes realizar cualquier acción que necesites al manejar el evento
        // Por ejemplo, enviar el mensaje a través de Pusher u otro servicio de tiempo real
        // También puedes acceder a la propiedad $event->mensaje para obtener el mensaje

        // Ejemplo de envío del mensaje a través de Pusher
        $this->pusher->trigger('chat-room', 'mensaje.guardado', ['mensaje' => $event->mensaje]);
    }
}
