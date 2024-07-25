<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password as PasswordRules;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "nombre" => ["required", "string"],
            "apellido" => ["required", "string"],
            "numero_documento" => ["required", "string", "unique:users,numero_documento", "regex:/^\d{8}(?:\d{2})?$/"],
            "usuario" => ["required", "string", "unique:users,usuario"],
            "fecha_nacimiento" => ["required", "string"],
            "direccion" => ["required", "string"],
            "email" => ["required", "email", "unique:users,email"],
            "imagen" => ["string"],
            "password" => [
                "required",
                "confirmed",
                PasswordRules::min(8)->letters()->symbols()->numbers()
            ],
            "id_tipo_documento" => ["required", "exists:tipo_documentos,id"],
            "id_rol" => ["required", "exists:roles,id"],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ser una dirección de correo electrónico válida.',
            'email.unique' => 'El correo electrónico ya está en uso.',
            'apellido.required' => 'El apellido es obligatorio.',
            'numero_documento.required' => 'El número de documento es obligatorio.',
            'numero_documento.min' => 'El número de documento debe tener al menos :min caracteres.',
            'numero_documento.unique' => 'El número de documento ya está en uso.',
            'numero_documento.max' => 'El número de documento no puede tener más de :max caracteres.',
            'usuario.required' => 'El nombre de usuario es obligatorio.',
            'usuario.unique' => 'El nombre de usuario ya está en uso.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.date' => 'Debe ser una fecha válida.',
            'direccion.required' => 'La dirección es obligatoria.',
            'id_tipo_documento.required' => 'El tipo de documento es obligatorio.',
            'id_tipo_documento.exists' => 'El tipo de documento seleccionado no es válido.',
            'imagen.required' => 'La imagen es obligatoria.',
        ];
    }
}
