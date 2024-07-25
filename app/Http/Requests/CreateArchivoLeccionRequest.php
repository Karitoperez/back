<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateArchivoLeccionRequest extends FormRequest
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
            'nombre' => 'required|string',
            'id_leccion' => 'required|exists:lecciones,id',
            'tipo' => 'required|string',
            'ubicacion' => 'required|string',
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
            'nombre.required' => 'El nombre del archivo de lección es requerido.',
            'id_leccion.required' => 'El ID de la lección es requerido.',
            'id_leccion.exists' => 'La lección especificada no existe.',
            'tipo.required' => 'El tipo del archivo de lección es requerido.',
            'ubicacion.required' => 'La ubicación del archivo de lección es requerida.',
        ];
    }
}
