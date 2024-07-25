<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoConversacion extends Model
{
    use HasFactory;
    
    protected $table = "tipos_conversacion";
    protected $fillable = ["nombre"];


}
