<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\CreateCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoriaController extends Controller
{
    /**
     * Obtener todas las categorías.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $categorias = Categoria::with('cursos')->get();

            return response()->json([
                'categorias' => $categorias
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al obtener las categorias: ' . $e->getMessage()
            ], 500);
        }
    }
    public function mostrarCategorias(): JsonResponse
    {
        try {
            $categorias = Categoria::with('cursos')->get();

            return response()->json([
                'categorias' => $categorias
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al obtener las categorias: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Almacenar una nueva categoría.
     *
     * @param  CreateCategoriaRequest  $request
     * @return JsonResponse
     */
    public function store(CreateCategoriaRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $categoria = Categoria::create($data);

            return response()->json([
                'message' => 'Categoría creada correctamente',
                'categoria' => $categoria,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al crear la categoría: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar una categoría por su ID.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $categoria = Categoria::findOrFail($id);

            return response()->json([
                'categoria' => $categoria
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'La categoría no existe'], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al obtener la categoría: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar una categoría existente.
     *
     * @param  UpdateCategoriaRequest  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(UpdateCategoriaRequest $request, $id): JsonResponse
    {
        try {
            $categoria = Categoria::findOrFail($id);

            $data = $request->validated();

            $categoria->update($data);

            return response()->json([
                'message' => 'Categoría actualizada correctamente',
                'categoria' => $categoria
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'La categoría no existe'], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar la categoría: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar una categoría existente.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $categoria = Categoria::findOrFail($id);
            $categoria->delete();

            return response()->json([
                'message' => 'Categoría eliminada correctamente',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'La categoría no existe'], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar la categoría: ' . $e->getMessage()
            ], 500);
        }
    }
}
