<?php

namespace App\Http\Controllers;

use App\Events\EnviarMensaje;
use Illuminate\Http\Request;
use App\Models\Conversacion;
use App\Models\Mensaje;
use App\Models\ParticipantesConversacion;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // Método para mostrar la lista de conversaciones del usuario
    public function index()
    {
        // Obtener todas las conversaciones del usuario autenticado
        $conversaciones = ParticipantesConversacion::where('id_usuario', Auth::id())
            ->with('conversacion.mensajes') // Cargar los mensajes de cada conversación
            ->get();

        // Verificar si hay conversaciones
        if ($conversaciones->isEmpty()) {
            return response()->json([
                'message' => 'No has creado conversaciones.'
            ]);
        }

        // Retornar las conversaciones con sus mensajes
        return response()->json([
            'conversaciones' => $conversaciones
        ]);
    }

    // Método para mostrar los mensajes de una conversación
    public function show(Conversacion $conversacion)
    {
        // Verificar si el usuario autenticado participa en la conversación
        if (!$conversacion->participantes()->where('id_usuario', Auth::id())->exists()) {
            abort(403, 'No tienes permiso para ver esta conversación.');
        }

        // Obtener los mensajes y los usuarios de la conversación
        $conversacion->load('mensajes.usuario', 'participantes.user');

        return response()->json([
            'conversacion' => $conversacion
        ]);
    }

    // Método para enviar un mensaje a una conversación
    public function enviarMensaje(Request $request, Conversacion $conversacion)
    {
        // Verificar si el usuario autenticado participa en la conversación
        if (!$conversacion->participantes()->where('id_usuario', Auth::id())->exists()) {
            abort(403, 'No tienes permiso para enviar mensajes en esta conversación.');
        }

        // Crear un nuevo mensaje
        $mensaje = new Mensaje();
        $mensaje->mensaje = $request->mensaje;
        $mensaje->estado = $request->estado;
        $mensaje->id_conversacion = $conversacion->id;
        $mensaje->id_usuario = Auth::id();
        $mensaje->save();

        // Obtener los mensajes y los usuarios de la conversación
        $conversacion->load('mensajes.usuario', 'participantes.user');

        EnviarMensaje::dispatch("mensaje");

        return response()->json([
            'conversacion' => $conversacion,
            'mensaje' => $mensaje
        ]);
    }

    // Método para obtener las conversaciones del usuario
    public function obtenerConversaciones()
    {
        // Obtener las conversaciones del usuario autenticado
        $conversaciones = ParticipantesConversacion::where('id_usuario', Auth::id())
            ->with('conversacion.mensajes') // Cargar los mensajes de cada conversación
            ->get();

            
        // Si el usuario no tiene conversaciones, devolver un mensaje para crear una nueva
        if ($conversaciones->isEmpty()) {
            return response()->json([
                'message' => 'No tienes conversaciones. ¡Crea una nueva!'
            ]);
        }

        // Retornar las conversaciones con sus mensajes
        return response()->json([
            'conversaciones' => $conversaciones
        ]);
    }

    // Método para crear una nueva conversación
    public function crearConversacion(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'usuarios' => 'required|array|min:2', // al menos dos usuarios deben estar involucrados
            'usuarios.*' => 'exists:users,id', // asegurar que todos los usuarios existan en la base de datos
        ]);

        // Crear una nueva conversación
        $conversacion = new Conversacion();
        $conversacion->estado = 1;
        $conversacion->id_tipo_conversacion = 1;
        $conversacion->save();

        // Agregar participantes a la conversación
        foreach ($request->usuarios as $usuarioId) {
            ParticipantesConversacion::create([
                'id_conversacion' => $conversacion->id,
                'id_usuario' => $usuarioId,
            ]);
        }

        // Obtener los mensajes y los usuarios de la conversación
        $conversacion->load('mensajes.usuario', 'participantes.user');
        $usuario = Auth::user()->load('cursosEstudiante', 'comentarios', 'conversaciones');

        // Retornar la conversación recién creada
        return response()->json([
            'message' => 'Conversación creada exitosamente.',
            'conversacion' => $conversacion,
            "usuario" => $usuario
        ]);
    }
}
