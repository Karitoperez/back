<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Testing\Fluent\Concerns\Has;
use Laravel\Sanctum\HasApiTokens;

class Comentarios extends Model
{
    use HasFactory, HasApiTokens, Notifiable;
    
    protected $fillable = [  
     
     'id_evaluacion',
     'id_usuario',
     'contenido',
     'fecha',
     'estado',
   
        ];
    


}