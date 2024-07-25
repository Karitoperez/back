<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversacion extends Model
{
    use HasFactory;


    protected $table = "conversaciones";
    protected $fillable = ["estado", "id_tipo_conversacion"];

    public function mensajes()
    {
        return $this->hasMany(Mensaje::class, 'id_conversacion');
    }    

    public function participantes()
    {
        return $this->hasMany(ParticipantesConversacion::class, 'id_conversacion');
    }

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'participantes_conversacion', 'id_conversacion', 'id_usuario');
    }
}
