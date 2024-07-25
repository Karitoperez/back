<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInscripcionRequest extends FormRequest
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
            "id_estudiante" => ["nullable", "exists:users,id"],
            "id_curso" => ["nullable", "exists:cursos,id"],
            "estado" => ["nullable", "boolean"],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            "id_estudiante.exists" => "El estudiante seleccionado no existe.",
            "id_curso.exists" => "El curso seleccionado no existe.",
            "estado.boolean" => "El estado debe ser verdadero o falso.",
        ];
    }
}