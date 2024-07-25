<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CursoEstudiante extends Model
{
    use HasFactory;

    protected $table = "curso_estudiante";
    protected $fillable = ["id_curso", "id_estudiante"];
}
