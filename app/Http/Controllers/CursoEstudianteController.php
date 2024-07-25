<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCursoEstudianteRequest;
use App\Http\Requests\CreateCursoEstudianteRequest;
use App\Http\Controllers\Controller;
use App\Models\CursoEstudiante;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
class CursoEstudianteController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            // Obtener todas las evaluaciones
            $cursoEStudiante =CursoEstudiante::all();
         
            // Devolver la respuesta JSON con las evaluaciones
            return response()->json([
                'matriculas' => $cursoEStudiante
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
            $cursoEStudiante = CursoEstudiante::findOrFail($id);

            return response()->json([
                'nota_estudiante' => $cursoEStudiante
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
    public function store(CreateCursoEstudianteRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
          
            $cursoEstudiante = CursoEstudiante::create([

                'id_estudiante'=> $data['id_estudiante'],
                'id_curso'=> $data['id_curso'], 
                'fecha_inscripcion'=> $data['fecha_inscripcion'],
                'estado'=> $data['estado'],
            ]);

            return response()->json([
                'message' => 'Nota registrada correctamente',
                'token' => $cursoEstudiante->createToken("token")->plainTextToken,
                'comentario' => $cursoEstudiante
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
    public function update(UpdateCursoEstudianteRequest $request, $id): JsonResponse
    {
        try {
            
            $cursoEstudiante = CursoEstudiante::findOrFail($id);
 
            $data = $request->validated();
 
            
            $cursoEstudiante->update([
                'id_estudiante'=> $data['id_estudiante'],
                'id_curso'=> $data['id_curso'], 
                'fecha_inscripcion'=> $data['fecha_inscripcion'],
                'estado'=> $data['estado'],
        
            ]);
 
            return response()->json([
                'message' => 'nota registrado correctamente',
                'token' => $cursoEstudiante->createToken("token")->plainTextToken,
                'evaluacion' => $cursoEstudiante
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
    public function destroy($id)
    {
        try {
            // Encuentra el usuario por su ID
           $cursoEstudiantes = CursoEstudiante::findOrFail($id);
            // Verificar si el usuario existe
            if (!$cursoEstudiantes) {
                return response()->json([
                    'message' => 'la evaluacion no existe'
                ], 404); // Código de estado HTTP 404 para indicar que el recurso no se encontró
            }
 
            // Si el usuario existe, intentar eliminarlo
            $cursoEstudiantes->delete();
 
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