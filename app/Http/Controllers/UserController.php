<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Models\Rol;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Obtener todos los usuarios.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            // Obtener todos los usuarios con sus cursos relacionados
            $usuarios = User::with('cursosEstudiante', 'rol', "conversaciones")->get();

            return response()->json([
                'usuarios' => $usuarios,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los usuarios: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar un usuario por su ID.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            // Buscar el usuario por su ID con sus cursos relacionados
            $usuario = User::with("cursosEstudiante")->findOrFail($id);

            return response()->json([
                'usuario' => $usuario
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'El usuario no existe'], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al obtener el usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Registrar un nuevo usuario.
     *
     * @param  CreateUserRequest  $request
     * @return JsonResponse
     */
    public function store(CreateUserRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            // Crear un nuevo usuario con los datos proporcionados
            $usuario = User::create([
                'name' => $data['nombre'],
                'apellido' => $data['apellido'],
                'numero_documento' => $data['numero_documento'],
                'usuario' => $data['usuario'],
                'fecha_nacimiento' => $data['fecha_nacimiento'],
                'direccion' => $data['direccion'],
                'id_tipo_documento' => $data['id_tipo_documento'],
                'id_rol' => $data['id_rol'],
                'imagen' => $data["imagen"],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            return response()->json([
                'message' => 'Usuario registrado correctamente',
                'token' => $usuario->createToken("token")->plainTextToken,
                'usuario' => $usuario
            ], 201);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();

            return response()->json([
                'message' => 'Error al registrar el usuario: ' . $e->getMessage(),
                'errors' => $errors
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al registrar el usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar un usuario existente.
     *
     * @param  UpdateUserRequest  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, $id): JsonResponse
    {
        try {
            // Buscar el usuario por su ID con sus cursos relacionados
            $usuario = User::with('cursosEstudiante')->findOrFail($id);

            $data = $request->validated();

            // Actualizar el usuario con los datos proporcionados
            $usuario->update([
                'name' => $data['nombre'],
                'apellido' => $data['apellido'],
                'numero_documento' => $data['numero_documento'],
                'usuario' => $data['usuario'],
                'fecha_nacimiento' => $data['fecha_nacimiento'],
                'direccion' => $data['direccion'],
                'email' => $data['email'],
                'imagen' => $data['imagen'],
                'id_tipo_documento' => $data['id_tipo_documento']
            ]);

            // Recargar el usuario con sus cursos actualizados
            $usuario->refresh();

            return response()->json([
                'message' => 'Usuario actualizado correctamente',
                "token" => $usuario->createToken("token")->plainTextToken,
                'usuario' => $usuario
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'El usuario no existe'], 404);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();

            return response()->json([
                'message' => 'Error al actualizar el usuario: ' . $e->getMessage(),
                'errors' => $errors
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un usuario existente.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            // Buscar el usuario por su ID
            $usuario = User::findOrFail($id);

            // Eliminar el usuario
            $usuario->delete();

            return response()->json([
                'message' => 'Usuario eliminado correctamente',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'El usuario no existe'], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Buscar usuarios por nombre, apellido, usuario o email.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function buscarUsuarios(Request $request): JsonResponse
    {
        try {
            // Obtener el término de búsqueda del parámetro "q" de la solicitud
            $query = $request["busqueda"];

            // Realizar la consulta para buscar usuarios que coincidan con el término de búsqueda
            $usuarios = User::with('cursosEstudiante', 'rol')
                            ->where('name', 'like', "%$query%")
                            ->orWhere('apellido', 'like', "%$query%")
                            ->orWhere('usuario', 'like', "%$query%")
                            ->orWhere('email', 'like', "%$query%")
                            ->get();

            return response()->json([
                'usuarios' => $usuarios,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al buscar usuarios: ' . $e->getMessage()
            ], 500);
        }
    }
}
