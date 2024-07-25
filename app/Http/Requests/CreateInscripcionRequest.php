<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateInscripcionRequest extends FormRequest
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
            "id_estudiante" => ["required", "exists:users,id"],
            "id_curso" => ["required", "exists:cursos,id"],
            "estado" => ["required", "boolean"],
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
            "id_estudiante.required" => "El estudiante es obligatorio.",
            "id_estudiante.exists" => "El estudiante seleccionado no existe.",
            "id_curso.required" => "El curso es obligatorio.",
            "id_curso.exists" => "El curso seleccionado no existe.",
            "estado.required" => "El estado es obligatorio.",
            "estado.boolean" => "El estado debe ser verdadero o falso.",
        ];
    }
}
