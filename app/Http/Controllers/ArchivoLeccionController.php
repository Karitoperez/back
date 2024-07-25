<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateArchivoLeccionRequest;
use App\Models\ArchivoLeccion;
use App\Models\Leccion;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArchivoLeccionController extends Controller
{
    /**
     * Almacenar un nuevo archivo de lección.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(CreateArchivoLeccionRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $archivoLeccion = ArchivoLeccion::create($data);

            // Obtener la lección a la que pertenece el archivo
            $leccion = Leccion::findOrFail($data['id_leccion'])->load('archivos');

            return response()->json([
                'message' => 'Archivo agregado correctamente a la lección.',
                "leccion" => $leccion
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al agregar el archivo a la lección: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un archivo de lección existente.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $archivoLeccion = ArchivoLeccion::findOrFail($id);
            $archivoLeccion->delete();

            return response()->json([
                'message' => 'Archivo eliminado correctamente de la lección.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el archivo de la lección: ' . $e->getMessage()
            ], 500);
        }
    }
}
