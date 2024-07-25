<?php

use App\Http\Controllers\ArchivoLeccionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\LeccionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;

;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí es donde puedes registrar las rutas de la API para tu aplicación.
| Estas rutas son cargadas por RouteServiceProvider y todas serán asignadas al
| grupo de middleware "api". ¡Haz algo genial!
|
*/

// Rutas para recursos protegidos por autenticación
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']); // Ruta para cerrar sesión
    Route::apiResource('/usuarios', UserController::class); // Rutas para CRUD de usuarios
    Route::post('/usuarios/buscar', [UserController::class, "buscarUsuarios"]);
    Route::apiResource('/cursos', CursoController::class)->except(['index', "show"]); // Rutas para CRUD de cursos
    Route::get('/cursos-populares', [CursoController::class, "cursosMasPopulares"]);
    Route::get('/cursos/docente/{idDocente}', [CursoController::class, 'cursosDelDocente']);
    Route::get('/cursos/estudiante/{idEstudiante}', [CursoController::class, 'cursosDelEstudiante']);
    Route::apiResource('/lecciones', LeccionController::class); // Rutas para CRUD de lecciones
    Route::apiResource('/categorias', CategoriaController::class)->except(['index']); // Rutas para CRUD de categorías
    Route::apiResource('/comentarios', ComentarioController::class); // Rutas para CRUD de comentarios
    Route::apiResource('/archivo-leccion', ArchivoLeccionController::class); // Rutas para CRUD de archivos de lecciones
    Route::apiResource('/inscripcion', InscripcionController::class); // Ruta para inscribirse en un curso
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{conversacion}', [ChatController::class, 'show']);
    Route::post('/chat/{conversacion}/enviar-mensaje', [ChatController::class, 'enviarMensaje']);
    Route::get('/chat/conversaciones', [ChatController::class, 'obtenerConversaciones']);
    Route::post('/chat/conversaciones/crear', [ChatController::class, 'crearConversacion']);
});
// Rutas públicas
Route::post('/login', [AuthController::class, 'login']); // Ruta para iniciar sesión
Route::post('/registrarse', [AuthController::class, 'store']); // Ruta para iniciar sesión
Route::get('/cursos', [CursoController::class, "index"]);
Route::get('/cursos/{curso}', [CursoController::class, "show"]);
Route::get('/categorias', [CategoriaController::class, "index"]); // Rutas para CRUD de categorías
