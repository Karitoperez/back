<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password as PasswordRules;
use Illuminate\Validation\Rule;


class UpdateUserRequest extends FormRequest
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

        // Obtén el ID del usuario de la ruta de la URL
        $userId = $this->route('usuario');

        return [
            "nombre" => ["required", "string"],
            "email" => ["required", "email", Rule::unique('users')->ignore($userId)],
            "apellido" => ["required", "string"],
            "numero_documento" => ["required", "string",  Rule::unique('users')->ignore($userId), "regex:/^\d{8}(?:\d{2})?$/"],
            "usuario" => ["required", "string", Rule::unique('users')->ignore($userId)],
            "fecha_nacimiento" => ["required", "date"],
            "direccion" => ["required", "string"],
            "id_tipo_documento" => ["required", "exists:tipo_documentos,id"],
            "imagen" => ["required", "string"],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ser una dirección de correo electrónico válida.',
            'email.unique' => 'El correo electrónico ya está en uso.',
            'apellido.required' => 'El apellido es obligatorio.',
            'numero_documento.required' => 'El número de documento es obligatorio.',
            "numero_documento.regex" => "El campo número de documento debe tener 8 o 10 dígitos.",
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
