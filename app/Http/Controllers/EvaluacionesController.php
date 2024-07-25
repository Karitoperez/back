<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateEvaluacionesRequest;
use App\Http\Requests\CreateEvaluacionesRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Evaluaciones;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
class EvaluacionesController extends Controller
{
  
    public function index(): JsonResponse
    {
        try {
            // Obtener todas las evaluaciones
            $evaluaciones =Evaluaciones::all();
         
            // Devolver la respuesta JSON con las evaluaciones
            return response()->json([
                'evaluaciones' => $evaluaciones
            ]);
        } catch (Exception $e) {
            // En caso de error, devolver una respuesta JSON con un mensaje de error
            return response()->json([
                'message' => 'Error al obtener las evaluaciones: ' . $e->getMessage()
            ], 500); // Código de estado HTTP 500 para indicar un error del servidor
        }
        
    }

    public function show($id): JsonResponse
    {
        try {
            
            $evaluaciones = Evaluaciones::findOrFail($id);
 
                        return response()->json([
                'evaluaciones' => $evaluaciones
            ]);
        } catch (ModelNotFoundException $e) {
            
            return response()->json(['message' => 'la evaluacion no existe'], 404);
        } catch (Exception $e) {
            
            return response()->json([
                'message' => 'Error al obtener evaluacion: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(CreateEvaluacionesRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
          
            $evaluaciones = Evaluaciones::create([
                'docente_id' => $data['docente_id'],
                'curso_id' => $data['curso_id'],
                'tipo' => $data['tipo'],
                'titulo' => $data['titulo'],
                'descripcion' => $data['descripcion'],
                'nota_maxima' => $data['nota_maxima'],
                'fecha_inicio' => $data['fecha_inicio'],
                'fecha_fin' => $data['fecha_fin'],
                'estado' => $data['estado'],
            ]);
 
            return response()->json([
                'message' => 'Evaluacion registrada correctamente',
                'token' => $evaluaciones->createToken("token")->plainTextToken,
                'evaluacion' => $evaluaciones
            ], 201); // Código de estado HTTP 201 para indicar éxito en la creación
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            // En caso de error, devolver una respuesta JSON con un mensaje de error
            return response()->json([
                'message' => 'Error al registrar la evaluacion: ' . $e->getMessage(),
                'errors' => $errors
            ], 422); // Código de estado HTTP 422 para indicar una solicitud mal formada debido a errores de validación
        } catch (Exception $e) {
            // En caso de otros errores, devuelve un mensaje genérico de error
            return response()->json([
                'message' => 'Error al registrar la evalaucion: ' . $e->getMessage()
            ], 500); // Código de estado HTTP 500 para indicar un error del servidor
        }
       
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEvaluacionesRequest $request, $id): JsonResponse
    {
        try {
            
            $evaluaciones = Evaluaciones::findOrFail($id);
 
            $data = $request->validated();
 
            
            $evaluaciones->update([
                'docente_id' => $data['docente_id'],
                'curso_id' => $data['curso_id'],
                'tipo' => $data['tipo'],
                'titulo' => $data['titulo'],
                'descripcion' => $data['descripcion'],
                'nota_maxima' => $data['nota_maxima'],
                'fecha_inicio' => $data['fecha_inicio'],
                'fecha_fin' => $data['fecha_fin'],
                'estado' => $data['estado'],
        
            ]);
 
            return response()->json([
                'message' => 'Evaluacion registrado correctamente',
                'token' => $evaluaciones->createToken("token")->plainTextToken,
                'evaluacion' => $evaluaciones
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse 
    {
        try {
            // Encuentra el usuario por su ID
           $evaluaciones = Evaluaciones::findOrFail($id);
            // Verificar si el usuario existe
            if (!$evaluaciones) {
                return response()->json([
                    'message' => 'la evaluacion no existe'
                ], 404); // Código de estado HTTP 404 para indicar que el recurso no se encontró
            }
 
            // Si el usuario existe, intentar eliminarlo
            $evaluaciones->delete();
 
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
