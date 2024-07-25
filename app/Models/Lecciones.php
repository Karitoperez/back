<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Lecciones extends Model
{
    use HasFactory, HasApiTokens, Notifiable;
    
    protected $fillable = [  
        'titulo',
        'id_docente',
        'id_curso',
        'descripcion',
        'contenido',
        'imagen',
        'estado',
        'fecha_inicio',
        'fecha_fin',
 
   
        ];

}
