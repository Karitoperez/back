<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCursoRequest extends FormRequest
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
            "titulo" => ["required", "string"],
            "fecha_inicio" => ["required", "date"],
            "fecha_fin" => ["required", "date", "after_or_equal:fecha_inicio"],
            "estado" => ["required", "boolean"],
            "imagen" => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            "descripcion" => ["required", "string"],
            "duracion" => ["required", "numeric", "min:1"],
            "id_docente" => ["nullable", "exists:users,id"],
            "id_categoria" => ["nullable", "exists:categorias,id"],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'titulo.required' => 'El título del curso es obligatorio.',
            'titulo.string' => 'El título del curso debe ser una cadena de caracteres.',
            'fecha_inicio.required' => 'La fecha de inicio del curso es obligatoria.',
            'fecha_inicio.date' => 'La fecha de inicio del curso debe ser una fecha válida.',
            'fecha_fin.required' => 'La fecha de finalización del curso es obligatoria.',
            'fecha_fin.date' => 'La fecha de finalización del curso debe ser una fecha válida.',
            'fecha_fin.after_or_equal' => 'La fecha de finalización del curso debe ser posterior o igual a la fecha de inicio.',
            'estado.required' => 'El estado del curso es obligatorio.',
            'estado.boolean' => 'El estado del curso debe ser verdadero o falso.',
            'imagen.required' => 'La imagen del curso es obligatoria.',
            'imagen.string' => 'La imagen del curso debe ser una cadena de caracteres.',
            'descripcion.required' => 'La descripción del curso es obligatoria.',
            'descripcion.string' => 'La descripción del curso debe ser una cadena de caracteres.',
            'duracion.required' => 'La duración del curso es obligatoria.',
            'duracion.numeric' => 'La duración del curso debe ser un valor numérico.',
            'duracion.min' => 'La duración del curso debe ser al menos 1.',
            'id_docente.exists' => 'El docente seleccionado no existe.',
            'id_categoria.exists' => 'La categoría seleccionada no existe.',
        ];
    }
}
