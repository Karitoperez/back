<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    use HasFactory;

    protected $table = "mensajes";
    protected $fillable = ["mensaje", "estado", "id_conversacion", "id_usuario"];

    public function conversacion()
    {
        return $this->belongsTo(Conversacion::class, "id_conversacion");
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }    
}
