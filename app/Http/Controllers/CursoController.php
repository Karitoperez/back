<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Http\Requests\CreateCursoRequest;
use App\Http\Requests\UpdateCursoRequest;
use App\Models\CursoEstudiante;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CursoController extends Controller
{
    /**
     * Obtener todos los cursos.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            // Obtener todos los cursos con sus relaciones
            $cursos = Curso::with("comentarios", "comentarios.user", "docente", "lecciones", "categoria", "estudiantes")
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'cursos' => $cursos,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los cursos: ' . $e->getMessage()
            ], 500);
        }
    }
    public function mostrarCursos(): JsonResponse
    {
        try {
            // Obtener todos los cursos con sus relaciones
            $cursos = Curso::with("docente", "lecciones", "categoria", "comentarios", "comentarios.user", "estudiantes")
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'cursos' => $cursos,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los cursos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar un curso por su ID.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            // Buscar el curso por su ID con sus relaciones
            $curso = Curso::with('docente', 'lecciones', 'categoria', 'comentarios', 'comentarios.user', 'estudiantes')
                ->findOrFail($id);

            return response()->json([
                'curso' => $curso
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'El curso no existe'], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al obtener el curso: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Registrar un nuevo curso.
     *
     * @param  CreateCursoRequest  $request
     * @return JsonResponse
     */
    // app/Http/Controllers/CursoController.php

    public function store(CreateCursoRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            // Manejar la carga de la imagen
            if ($request->hasFile('imagen')) {
                $path = $request->file('imagen')->store('imagenes', 'public');
                $data['imagen'] = $path;
            }

            // Crear un nuevo curso con los datos proporcionados
            $curso = Curso::create([
                'titulo' => $data['titulo'],
                'imagen' => $data['imagen'],
                'duracion' => $data['duracion'],
                'estado' => $data['estado'],
                'fecha_inicio' => $data['fecha_inicio'],
                'fecha_fin' => $data['fecha_fin'],
                'descripcion' => $data['descripcion'],
                'id_docente' => $data['id_docente'],
                'id_categoria' => $data['id_categoria'],
            ]);

            return response()->json([
                'message' => 'Curso agregado correctamente',
                'curso' => $curso
            ], 201);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();

            return response()->json([
                'message' => 'Error al agregar el curso: ' . $e->getMessage(),
                'errors' => $errors
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al agregar el curso: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Actualizar un curso existente.
     *
     * @param  UpdateCursoRequest  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(UpdateCursoRequest $request, $id): JsonResponse
    {
        try {
            // Encuentra el curso por su ID
            $curso = Curso::findOrFail($id);

            $data = $request->validated();

            // Actualizar el curso con los datos proporcionados
            $curso->update([
                'titulo' => $data['titulo'],
                'imagen' => $data['imagen'],
                'duracion' => $data['duracion'],
                'estado' => $data['estado'],
                'fecha_inicio' => $data['fecha_inicio'],
                'fecha_fin' => $data['fecha_fin'],
                'descripcion' => $data['descripcion'],
                'id_categoria' => $data['id_categoria'],
            ]);

            return response()->json([
                'message' => 'Curso actualizado correctamente',
                'curso' => $curso
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'El curso no existe'], 404);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();

            return response()->json([
                'message' => 'Error al actualizar el curso: ' . $e->getMessage(),
                'errors' => $errors
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el curso: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un curso existente.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            // Buscar el curso por su ID
            $curso = Curso::findOrFail($id);

            // Eliminar la relación en curso_estudiante
            CursoEstudiante::where('id_curso', $id)->delete();

            // Eliminar el curso
            $curso->delete();

            return response()->json([
                'message' => 'Curso eliminado correctamente',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'El curso no existe'], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el curso: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cursosMasPopulares()
    {
        try {
            // Obtener los cursos ordenados por el número de estudiantes en orden descendente
            $cursosPopulares = Curso::with('docente', 'lecciones', 'categoria', 'comentarios')
                ->withCount('estudiantes')
                ->orderByDesc('estudiantes_count')
                ->take(8)
                ->get();


            return response()->json([
                'cursosPopulares' => $cursosPopulares,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los cursos más populares: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cursosDelDocente($idDocente): JsonResponse
    {
        try {
            // Buscar el docente por su ID
            $docente = User::findOrFail($idDocente);

            // Obtener todos los cursos del docente con sus relaciones
            $cursos = Curso::where('id_docente', $idDocente)
                ->with("docente", "lecciones", "categoria", "comentarios", "estudiantes")
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'cursos' => $cursos,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'El docente no existe'], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los cursos del docente: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener todos los cursos en los que está inscrito un estudiante específico.
     *
     * @param  int  $idEstudiante
     * @return JsonResponse
     */
    public function cursosDelEstudiante($idEstudiante): JsonResponse
    {
        try {
            // Buscar el estudiante por su ID
            $estudiante = User::findOrFail($idEstudiante);

            // Obtener todos los cursos del estudiante
            $cursos = $estudiante->cursosEstudiante()->with('docente', 'lecciones', 'categoria', 'comentarios', 'estudiantes')->get();


            return response()->json([
                'cursos' => $cursos,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'El estudiante no existe'], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los cursos del estudiante: ' . $e->getMessage()
            ], 500);
        }
    }
}
