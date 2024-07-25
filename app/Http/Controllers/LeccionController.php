<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLeccionRequest;
use App\Http\Requests\UpdateLeccionRequest;
use App\Models\Leccion;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class LeccionController extends Controller
{
    /**
     * Mostrar todas las lecciones.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $lecciones = Leccion::with("curso", "archivos")->orderBy('created_at', 'desc')->get();

            return response()->json([
                'lecciones' => $lecciones,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al obtener las lecciones: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar una lección específica.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $leccion = Leccion::with('curso', 'archivos')->findOrFail($id);

            return response()->json([
                'leccion' => $leccion
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'La lección no existe'], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al obtener la lección: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Almacenar una nueva lección.
     *
     * @param  CreateLeccionRequest  $request
     * @return JsonResponse
     */
    public function store(CreateLeccionRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $leccion = Leccion::create($data);

            return response()->json([
                'message' => 'Lección agregada correctamente',
                'leccion' => $leccion
            ], 201);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return response()->json([
                'message' => 'Error al agregar la lección: ' . $e->getMessage(),
                'errors' => $errors
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al agregar la lección: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar una lección existente.
     *
     * @param  UpdateLeccionRequest  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(UpdateLeccionRequest $request, $id): JsonResponse
    {
        try {
            $leccion = Leccion::findOrFail($id);
            $data = $request->validated();
            $leccion->update($data);

            return response()->json([
                'message' => 'Lección actualizada correctamente',
                'leccion' => $leccion
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'La lección no existe'], 404);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return response()->json([
                'message' => 'Error al actualizar la lección: ' . $e->getMessage(),
                'errors' => $errors
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar la lección: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar una lección existente.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $leccion = Leccion::findOrFail($id);
            $leccion->delete();

            return response()->json([
                'message' => 'Lección eliminada correctamente',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar la lección: ' . $e->getMessage()
            ], 500);
        }
    }
}
