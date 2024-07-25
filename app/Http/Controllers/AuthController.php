<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \App\Http\Requests\LoginRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        // Validar la solicitud de inicio de sesión
        $credenciales = $request->validated();

        // Intentar autenticar al usuario
        if (!Auth::attempt($credenciales)) {
            return Response::json([
                'message' => 'Credenciales incorrectas.'
            ], 401);
        }        

        // Autenticación exitosa, cargar información adicional del usuario
        $usuario = Auth::user()->load('cursosEstudiante', 'comentarios', 'conversaciones.usuarios', 'conversaciones.participantes');
        $token = $usuario->createToken('Token')->plainTextToken;

        // Generar y retornar un token de acceso
        return Response::json([
            'token' => $token,
            'usuario' => $usuario
        ], 200);
    }

    /**
     * Handle user logout.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        // Obtener el usuario actualmente autenticado
        $usuario = $request->user();

        // Verificar si el usuario está autenticado
        if (!$usuario) {
            return Response::json([
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        // Revocar el token de acceso actual
        $usuario->currentAccessToken()->delete();

        return Response::json([
            'message' => 'Usuario desconectado exitosamente'
        ], 200);
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
}
