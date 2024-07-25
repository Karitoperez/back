<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchivoLeccion extends Model
{
    use HasFactory;

    protected $table = 'archivos_leccion';

    protected $fillable = [
        'tipo',
        'nombre',
        'ubicacion',
        'id_leccion',
    ];

    public function leccion()
    {
        return $this->belongsTo(Leccion::class, 'id');
    }
}
