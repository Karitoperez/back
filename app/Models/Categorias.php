<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorias extends Model
{
    use HasFactory;

    protected $table = 'categorias';
    protected $fillable = [
        'nombre',
    ];

    public function cursos()
    {
        return $this->hasMany(Cursos::class, 'id_categoria');
    }
}