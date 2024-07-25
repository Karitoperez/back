<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    protected $table = 'cursos';
    protected $fillable = [
        'titulo',
        'imagen',
        'duracion',
        'estado',
        'fecha_inicio',
        'fecha_fin',
        'descripcion',
        'id_docente',
        'id_categoria'
    ];

    public function docente()
    {
        return $this->belongsTo(User::class, 'id_docente');
    }

    public function lecciones()
    {
        return $this->hasMany(Leccion::class, 'id_curso');
    }
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    public function comentarios()
    {
        return $this->morphMany(Comentario::class, 'commentable');
    }

    public function estudiantes()
    {
        return $this->belongsToMany(User::class, 'curso_estudiante', 'id_curso', 'id_estudiante');
    }
}
