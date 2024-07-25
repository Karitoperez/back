<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateComentarioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id_usuario' => ['required'],
            'comentario' => ['required', 'string'],
            'calificacion' => ['required', 'numeric', 'min:1', 'max:5'],
            'commentable_type' => ['required'],
            'commentable_id' => ['required'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'id_usuario.required' => 'El ID del estudiante es requerido.',
            'comentario.required' => 'El comentario es requerido.',
            'comentario.string' => 'El comentario debe ser una cadena de caracteres.',
            'calificacion.required' => 'La calificación es requerida.',
            'calificacion.numeric' => 'La calificación debe ser un valor numérico.',
            'calificacion.min' => 'La calificación debe ser al menos :min.',
            'calificacion.max' => 'La calificación no puede ser mayor que :max.',
            'commentable_type.required' => 'El tipo de recurso para el comentario es requerido.',
            'commentable_id.required' => 'El ID del recurso para el comentario es requerido.',
        ];
    }
}
