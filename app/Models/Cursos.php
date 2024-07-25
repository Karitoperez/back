<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Cursos extends Model
{
    use HasFactory;

    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
       
    'id_docente',
    'id_categoria',
    'titulo',
    'descripcion',
    'imagen',
    'duracion',
    'estado',
    'fecha_inicio',
    'fecha_fin',
    ];
}
