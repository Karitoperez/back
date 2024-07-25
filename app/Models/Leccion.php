<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leccion extends Model
{
    use HasFactory;

    protected $table = "lecciones";
    protected $fillable = ["titulo", "descripcion", "orden", "imagen", "estado", "id_curso", "id_docente"];


    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso');
    }

    public function comentarios()
    {
        return $this->morphMany(Comentario::class, 'commentable');
    }

    public function archivos()
    {
        return $this->hasMany(ArchivoLeccion::class, 'id_leccion');
    }
}
