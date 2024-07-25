<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateMatriculasRequest;
use App\Http\Requests\CreateMatriculasRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Matriculas;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class MatriculasController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            // Obtener todas las evaluaciones
            $matriculas =Matriculas::all();
         
            // Devolver la respuesta JSON con las evaluaciones
            return response()->json([
                'matriculas' => $matriculas
            ]);
        } catch (Exception $e) {
            // En caso de error, devolver una respuesta JSON con un mensaje de error
            return response()->json([
                'message' => 'Error al obtener las matriculas: ' . $e->getMessage()
            ], 500); // Código de estado HTTP 500 para indicar un error del servidor
        }
        
    }

    public function show($id): JsonResponse
    {
        try {
            
            $matriculas = Matriculas::findOrFail($id);
 
                        return response()->json([
                'matriculas' => $matriculas
            ]);
        } catch (ModelNotFoundException $e) {
            
            return response()->json(['message' => 'la matricula no existe'], 404);
        } catch (Exception $e) {
            
            return response()->json([
                'message' => 'Error al obtener matricula: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(CreateMatriculasRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
          
            $matriculas = Matriculas::create([
                'estudiante_id'=> $data['estudiante_id'],
                'curso_id'=> $data['curso_id'],
                'fecha'=> $data['fecha'],
                'estado'=> $data['estado'],

            ]);
 
            return response()->json([
                'message' => 'Matricula registrada correctamente',
                'token' => $matriculas->createToken("token")->plainTextToken,
                'evaluacion' => $matriculas
            ], 201); // Código de estado HTTP 201 para indicar éxito en la creación
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            // En caso de error, devolver una respuesta JSON con un mensaje de error
            return response()->json([
                'message' => 'Error al registrar la matricula: ' . $e->getMessage(),
                'errors' => $errors
            ], 422); // Código de estado HTTP 422 para indicar una solicitud mal formada debido a errores de validación
        } catch (Exception $e) {
            // En caso de otros errores, devuelve un mensaje genérico de error
            return response()->json([
                'message' => 'Error al registrar la matricula: ' . $e->getMessage()
            ], 500); // Código de estado HTTP 500 para indicar un error del servidor
        }
       
    }

    public function update(UpdateMatriculasRequest $request, $id): JsonResponse
    {
        try {
            
            $matriculas = Matriculas::findOrFail($id);
 
            $data = $request->validated();
 
            
            $matriculas->update([
                'estudiante_id'=> $data['estudiante_id'],
                'curso_id'=> $data['curso_id'],
                'fecha'=> $data['fecha'],
                'estado'=> $data['estado'],
            ]);
 
            return response()->json([
                'message' => 'Evaluacion registrado correctamente',
                'token' => $matriculas->createToken("token")->plainTextToken,
                'evaluacion' => $matriculas
            ], 201); // Código de estado HTTP 201 para indicar éxito en la creación

           
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'la evaluacion no existe'], 404);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            // En caso de error, devolver una respuesta JSON con un mensaje de error
            return response()->json([
                'message' => 'Error al actualizar la evalicion: ' . $e->getMessage(),
                'errors' => $errors
            ], 422);
        }
    }



    public function destroy($id): JsonResponse 
    {
        try {
            // Encuentra el usuario por su ID
           $matriculas = Matriculas::findOrFail($id);
            // Verificar si el usuario existe
            if (!$matriculas) {
                return response()->json([
                    'message' => 'la evaluacion no existe'
                ], 404); // Código de estado HTTP 404 para indicar que el recurso no se encontró
            }
 
            // Si el usuario existe, intentar eliminarlo
            $matriculas->delete();
 
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
