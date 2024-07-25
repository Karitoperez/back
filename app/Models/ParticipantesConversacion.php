<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipantesConversacion extends Model
{
    use HasFactory;

    protected $table = "participantes_conversacion";
    protected $fillable = ["id_conversacion", "id_usuario"];

    public function conversacion()
    {
        return $this->belongsTo(Conversacion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, "id_usuario");
    }

    public function participantes()
    {
        return $this->hasMany(ParticipantesConversacion::class, 'id_conversacion');
    }
}
