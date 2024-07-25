<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Evaluaciones extends Model
{
        use HasFactory, HasApiTokens, Notifiable;

        protected $fillable = [
                'id_docente',
                'id_curso',
                'tipo',
                'titulo',
                'descripcion',
                'nota_maxima',
                'fecha_inicio',
                'fecha_fin',
                'estado',
        ];
}
