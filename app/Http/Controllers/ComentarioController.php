<?php

namespace App\Http\Controllers;

use App\Helpers\AuthHelper;
use App\Models\Comentario;
use App\Http\Requests\CreateComentarioRequest;
use App\Http\Requests\UpdateComentarioRequest;
use App\Models\Curso;
use App\Models\Leccion;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;

class ComentarioController extends Controller
{
    /**
     * Almacena un nuevo comentario.
     *
     * @param  \App\Http\Requests\CreateComentarioRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateComentarioRequest $request): JsonResponse
    {
        try {
            // Validar los datos de la solicitud
            $data = $request->validated();

            // Crear el comentario
            $comentario = $this->createComentario($data);

            // Obtener el curso asociado al comentario
            $curso = $comentario->commentable;

            // Obtener el curso asociado al comentario
            $curso = Curso::with("comentarios", "comentarios.user", "docente", "lecciones", "categoria", "estudiantes")->findOrFail($comentario->commentable_id);

            $usuario = User::with('cursosEstudiante', 'comentarios')->findOrFail($data["id_usuario"]);

            // Responder con un JSON que indica que el comentario se ha agregado correctamente
            return response()->json([
                'message' => 'Comentario agregado correctamente',
                'usuario' => $usuario, // Aquí obtienes el usuario de la sesión
                'comentario' => $comentario,
                'curso' => $curso,
            ], 201);
        } catch (Exception $e) {
            // Responder con un JSON que indica que ocurrió un error al agregar el comentario
            return response()->json([
                'message' => 'Error al agregar el comentario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualiza un comentario existente.
     *
     * @param  \App\Http\Requests\UpdateComentarioRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateComentarioRequest $request, $id): JsonResponse
    {
        try {
            // Encontrar el comentario por su ID
            $comentario = Comentario::with("user")->findOrFail($id);

            // Verificar si el usuario actual es el creador del comentario
            if (!AuthHelper::esUsuarioActual($comentario->id_usuario)) {
                // Si el usuario no es el creador del comentario, devolver un error de autorización
                return response()->json([
                    'message' => 'No tienes permiso para actualizar este comentario'
                ], 403); // Código de estado 403: Prohibido
            }

            // Validar los datos de la solicitud
            $data = $request->validated();

            $usuario = User::with('cursosEstudiante', 'comentarios')->findOrFail($comentario["id_usuario"]);

            // Actualizar el comentario
            $comentario = $this->updateComentario($comentario, $data);

            // Obtener el curso asociado al comentario
            $curso = Curso::with("comentarios", "comentarios.user", "docente", "lecciones", "categoria", "estudiantes")->findOrFail($comentario->commentable_id);

            // Responder con un JSON que indica que el comentario se ha actualizado correctamente
            return response()->json([
                'message' => 'Comentario actualizado correctamente',
                'comentario' => $comentario,
                'usuario' => $usuario,
                'curso' => $curso,
            ], 200);
        } catch (Exception $e) {
            // Responder con un JSON que indica que ocurrió un error al actualizar el comentario
            return response()->json([
                'message' => 'Error al actualizar el comentario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Elimina un comentario existente.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            // Encontrar el comentario por su ID
            $comentario = Comentario::findOrFail($id);

            // Verificar si el usuario tiene permisos para eliminar el comentario
            $this->authorize('delete', $comentario);

            // Eliminar el comentario
            $comentario->delete();

            // Responder con un JSON que indica que el comentario se ha eliminado correctamente
            return response()->json([
                'message' => 'Comentario eliminado correctamente',
            ]);
        } catch (Exception $e) {
            // Responder con un JSON que indica que ocurrió un error al eliminar el comentario
            return response()->json([
                'message' => 'Error al eliminar el comentario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Muestra un comentario específico.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            // Encontrar el comentario por su ID
            $comentario = Comentario::findOrFail($id);

            // Responder con un JSON que contiene el comentario encontrado
            return response()->json([
                'comentario' => $comentario,
            ]);
        } catch (Exception $e) {
            // Responder con un JSON que indica que ocurrió un error al encontrar el comentario
            return response()->json([
                'message' => 'Error al obtener el comentario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crea un nuevo comentario y lo asocia con un recurso (Curso o Leccion).
     *
     * @param  array  $data
     * @return \App\Models\Comentario
     */
    private function createComentario(array $data): Comentario
    {
        // Verificar si el tipo de recurso para el comentario es válido
        $commentableType = $data['commentable_type'];
        if (!in_array($commentableType, ['App\Models\Curso', 'App\Models\Leccion'])) {
            throw new Exception("El tipo de recurso para el comentario no es válido");
        }

        // Encontrar el recurso correspondiente (Curso o Leccion)
        if ($commentableType == 'App\Models\Curso') {
            $commentable = Curso::with("comentarios")->findOrFail($data['commentable_id']);
        } else {
            $commentable = Leccion::with("comentarios")->findOrFail($data['commentable_id']);
        }

        // Crear el comentario asociado al recurso
        $comentario = new Comentario([
            'id_usuario' => $data['id_usuario'],
            'comentario' => $data['comentario'],
            'calificacion' => $data['calificacion']
        ]);

        // Guardar el comentario asociado al recurso
        $commentable->comentarios()->save($comentario);

        return $comentario;
    }

    /**
     * Actualiza los datos de un comentario existente.
     *
     * @param  \App\Models\Comentario  $comentario
     * @param  array  $data
     * @return \App\Models\Comentario
     */
    private function updateComentario(Comentario $comentario, array $data): Comentario
    {
        // Actualizar los campos del comentario
        $comentario->update([
            'comentario' => $data['comentario'],
            'calificacion' => $data['calificacion']
        ]);

        return $comentario;
    }
}
