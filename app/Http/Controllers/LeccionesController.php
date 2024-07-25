<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateteLeccionesRequest;
use App\Http\Requests\CreateLeccionesRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lecciones;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
class LeccionesController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            // Obtener todas las evaluaciones
            $lecciones =Lecciones::all();
         
            // Devolver la respuesta JSON con las evaluaciones
            return response()->json([
                'matriculas' => $lecciones
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
            
            $lecciones = Lecciones::findOrFail($id);
 
                        return response()->json([
                'lecciones' => $lecciones
            ]);
        } catch (ModelNotFoundException $e) {
            
            return response()->json(['message' => 'la leccion no existe'], 404);
        } catch (Exception $e) {
            
            return response()->json([
                'message' => 'Error al obtener matricula: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(CreateLeccionesRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
          
            $lecciones = Lecciones::create([
                'titulo'=> $data['titulo'],
                'docente_id'=> $data['docente_id'],
                'curso_id'=> $data['curso_id'],
                'descripcion'=> $data['descripcion'],
                'contenido'=> $data['contenido'],
                'imagen'=> $data['imagen'],
                'estado'=> $data['estado'],
                'fecha_inicio'=> $data['fecha_inicio'],
                'fecha_fin'=> $data['fecha_fin'],

            ]);
 
            return response()->json([
                'message' => 'Matricula registrada correctamente',
                'token' => $lecciones->createToken("token")->plainTextToken,
                'evaluacion' => $lecciones
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

    public function update(UpdateteLeccionesRequest $request, $id): JsonResponse
    {
        try {
            
            $lecciones = Lecciones::findOrFail($id);
 
            $data = $request->validated();
 
            
            $lecciones->update([
                'titulo'=> $data['titulo'],
                'docente_id'=> $data['docente_id'],
                'curso_id'=> $data['curso_id'],
                'descripcion'=> $data['descripcion'],
                'contenido'=> $data['contenido'],
                'imagen'=> $data['imagen'],
                'estado'=> $data['estado'],
                'fecha_inicio'=> $data['fecha_inicio'],
                'fecha_fin'=> $data['fecha_fin'],
            ]);
 
            return response()->json([
                'message' => 'Evaluacion registrado correctamente',
                'token' => $lecciones->createToken("token")->plainTextToken,
                'evaluacion' => $lecciones
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
           $lecciones = Lecciones::findOrFail($id);
            // Verificar si el usuario existe
            if (!$lecciones) {
                return response()->json([
                    'message' => 'la evaluacion no existe'
                ], 404); // Código de estado HTTP 404 para indicar que el recurso no se encontró
            }
 
            // Si el usuario existe, intentar eliminarlo
            $lecciones->delete();
 
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
