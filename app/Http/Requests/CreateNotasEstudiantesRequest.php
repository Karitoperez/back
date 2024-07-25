<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateNotasEstudiantesRequest extends FormRequest
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
 
            
            "evaluacion_id" => ["required", "string", "nullable" ],
            "estudiante_id" => ["required", "string","nullable" ],
            "nota" => ["required", "string","nullable"],
        ];
    }
}
