<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCursosRequest extends FormRequest
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
            "titulo" => ["required", "string"],
            "fecha_inicio" => ["required", "string"],
            "fecha_fin" => ["required", "string"],
            "estado" => ["required", "boolean"],
            "imagen" => ["required", "string"],
            "descripcion" => ["required", "string"],
            "duracion" => ["required"],
            "docente_id" => ["nullable"],
            "categoria_id" => ["required"],
            
        ];
    }
}
