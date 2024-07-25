<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario autenticado es un estudiante
        if ($request->user() && $request->user()->id_rol === 1) {
            // Si es un estudiante, permite que la solicitud continúe
            return $next($request);
        }
        // Si el usuario no es un estudiante, redirige o responde con un error
        return response()->json(['message' => 'No autorizado para acceder a esta ruta'], 403);
    }
}
