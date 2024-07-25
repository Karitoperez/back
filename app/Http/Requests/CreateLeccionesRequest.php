<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateLeccionesRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "docente_id" => ["nullable", "exists:users,id"],
            "curso_id" => ["nullable", "exists:cursos,id"],
            "titulo" => ["required", "string"],
            "descripcion" => ["required", "string"],
            "contenido" => ["required", "string"],
            "estado" => ["required", "boolean"],
            "imagen" => ["required", "string"],
            "fecha_inicio" => ["required", "string"],
            "fecha_fin" => ["required", "string"],
              

           
        ];
    }
}
