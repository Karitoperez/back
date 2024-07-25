<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateNotasEstudiantesRequest;
use App\Http\Requests\CreateNotasEstudiantesRequest;
use App\Http\Controllers\Controller;
use App\Models\NotasEstudiantes;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class NotasEstudiantesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        try {
            // Obtener todas las notas de estudiantes
            $notasEstudiantes = NotasEstudiantes::all();
         
            // Devolver la respuesta JSON con las notas de estudiantes
            return response()->json([
                'notas' => $notasEstudiantes
            ]);
        } catch (Exception $e) {
            // En caso de error, devolver una respuesta JSON con un mensaje de error
            return response()->json([
                'message' => 'Error al obtener las notas de estudiantes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): JsonResponse
    {
        try {
            $notasEstudiantes = NotasEstudiantes::findOrFail($id);

            return response()->json([
                'nota_estudiante' => $notasEstudiantes
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'La nota de estudiante no existe'], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al obtener la nota de estudiante: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateNotasEstudiantesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateNotasEstudiantesRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
          
            $notasEstudiantes = NotasEstudiantes::create([
                'evaluacion_id' => $data['evaluacion_id'],
                'estudiante_id' => $data['estudiante_id'],
                'nota' => $data['nota'],
            ]);

            return response()->json([
                'message' => 'Nota registrada correctamente',
                'token' => $notasEstudiantes->createToken("token")->plainTextToken,
                'comentario' => $notasEstudiantes
            ], 201); // Código de estado HTTP 201 para indicar éxito en la creación
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            // En caso de error, devolver una respuesta JSON con un mensaje de error
            return response()->json([
                'message' => 'Error al registrar la nota: ' . $e->getMessage(),
                'errors' => $errors
            ], 422); // Código de estado HTTP 422 para indicar una solicitud mal formada debido a errores de validación
        } catch (Exception $e) {
            // En caso de otros errores, devuelve un mensaje genérico de error
            return response()->json([
                'message' => 'Error al registrar la nota: ' . $e->getMessage()
            ], 500); // Código de estado HTTP 500 para indicar un error del servidor
        } 
      

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNotasEstudiantesRequest $request, $id): JsonResponse
    {
        try {
            
            $notasEstudiantes = NotasEstudiantes::findOrFail($id);
 
            $data = $request->validated();
 
            
            $notasEstudiantes->update([
                'evaluacion_id' => $data['evaluacion_id'],
                'estudiante_id' => $data['estudiante_id'],
                'nota' => $data['nota'],
        
            ]);
 
            return response()->json([
                'message' => 'nota registrado correctamente',
                'token' => $notasEstudiantes->createToken("token")->plainTextToken,
                'evaluacion' => $notasEstudiantes
            ], 201); // Código de estado HTTP 201 para indicar éxito en la creación

           
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'la nota no existe'], 404);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            // En caso de error, devolver una respuesta JSON con un mensaje de error
            return response()->json([
                'message' => 'Error al actualizar la nota: ' . $e->getMessage(),
                'errors' => $errors
            ], 422);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // Encuentra el usuario por su ID
           $notasEstudiantes = NotasEstudiantes::findOrFail($id);
            // Verificar si el usuario existe
            if (!$notasEstudiantes) {
                return response()->json([
                    'message' => 'la evaluacion no existe'
                ], 404); // Código de estado HTTP 404 para indicar que el recurso no se encontró
            }
 
            // Si el usuario existe, intentar eliminarlo
            $notasEstudiantes->delete();
 
            return response()->json([
                'message' => 'Evaluacion eliminada correctamente',
            ]);
        } catch (Exception $e) {
            // En caso de error, devolver una respuesta JSON con un mensaje de error
            return response()->json([
                'message' => 'Error al eliminar la evaluacion: ' . $e->getMessage()
            ], 500); // Código de estado HTTP 500 para indicar un error del servidor
        }
    }
}
