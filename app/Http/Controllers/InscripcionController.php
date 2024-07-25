<?php

namespace App\Http\Controllers;

use App\Helpers\AuthHelper;
use App\Http\Requests\CreateInscripcionRequest;
use App\Http\Requests\UpdateInscripcionRequest;
use App\Models\CursoEstudiante;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class InscripcionController extends Controller
{

    /**
     * Muestra una lista de inscripciones.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            // Obtener todas las inscripciones
            $inscripciones = CursoEstudiante::all();

            return response()->json([
                'inscripciones' => $inscripciones
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al obtener las inscripciones: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(CreateInscripcionRequest $request): JsonResponse
    {
        // Verificar si el usuario es un estudiante
        if (!AuthHelper::esEstudiante()) {
            return response()->json([
                'message' => 'El usuario no es un estudiante.'
            ], 422);
        }
        try {
            $data = $request->validated();

            // Verificar si el usuario ya está inscrito en el curso
            if (CursoEstudiante::where('id_estudiante', $data['id_estudiante'])->where('id_curso', $data['id_curso'])->exists()) {
                return response()->json([
                    'message' => 'El estudiante ya está inscrito en este curso.'
                ], 422);
            }

            // Crear una nueva inscripción con los datos proporcionados
            $cursoEstudiante = CursoEstudiante::create([
                'id_estudiante' => $data['id_estudiante'],
                'id_curso' => $data['id_curso'],
                'estado' => $data['estado'],
            ]);

            // Obtener el usuario con todas sus inscripciones
            $usuario = User::with('cursosEstudiante', 'comentarios')->findOrFail($data['id_estudiante']);

            return response()->json([
                'message' => 'Inscripción realizada correctamente',
                'curso' => $cursoEstudiante,
                'usuario' => $usuario,
            ], 201); // Código de estado HTTP 201 para indicar éxito en la creación
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return response()->json([
                'message' => 'Error al realizar la inscripción: ' . $e->getMessage(),
                'errors' => $errors
            ], 422); // Código de estado HTTP 422 para indicar una solicitud mal formada debido a errores de validación
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al realizar la inscripción: ' . $e->getMessage()
            ], 500); // Código de estado HTTP 500 para indicar un error del servidor
        }
    }

    /**
     * Muestra una inscripción específica.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            // Buscar la inscripción por su ID
            $inscripcion = CursoEstudiante::findOrFail($id);

            return response()->json([
                'inscripcion' => $inscripcion
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al obtener la inscripción: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Actualiza una inscripción existente en el almacenamiento.
     *
     * @param  UpdateInscripcionRequest  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(UpdateInscripcionRequest $request, $id): JsonResponse
    {
        // Verificar si el usuario es un estudiante
        if (!AuthHelper::esEstudiante()) {
            return response()->json([
                'message' => 'El usuario no es un estudiante.'
            ], 422);
        }
        try {
            $inscripcion = CursoEstudiante::findOrFail($id);

            // Verificar si el usuario es el propietario de la inscripción
            if (!AuthHelper::estaUsuarioInscritoEnCurso($inscripcion->id_estudiante)) {
                return response()->json([
                    'message' => 'No tiene permisos para actualizar esta inscripción.'
                ], 403);
            }

            $data = $request->validated();

            $inscripcion->update($data);

            return response()->json([
                'message' => 'Inscripción actualizada correctamente',
                'inscripcion' => $inscripcion
            ]);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return response()->json([
                'message' => 'Error al actualizar la inscripción: ' . $e->getMessage(),
                'errors' => $errors
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar la inscripción: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Elimina una inscripción existente del almacenamiento.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        // Verificar si el usuario es un estudiante
        if (!AuthHelper::esEstudiante()) {
            return response()->json([
                'message' => 'El usuario no es un estudiante.'
            ], 422);
        }

        try {
            $inscripcion = CursoEstudiante::findOrFail($id);

            // Verificar si el usuario es el propietario de la inscripción
            if (!AuthHelper::estaUsuarioInscritoEnCurso($inscripcion->id_estudiante)) {
                return response()->json([
                    'message' => 'No tiene permisos para eliminar esta inscripción.'
                ], 403);
            }

            $inscripcion->delete();

            return response()->json([
                'message' => 'Inscripción eliminada correctamente'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar la inscripción: ' . $e->getMessage()
            ], 500);
        }
    }
}
