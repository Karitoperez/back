<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class AuthHelper
{
    public static function esAdmin()
    {
        return Auth::check() && Auth::user()->id_rol === 1;
    }

    public static function esEstudiante()
    {
        return Auth::check() && Auth::user()->id_rol === 2;
    }

    public static function esProfesor()
    {
        return Auth::check() && Auth::user()->id_rol === 3;
    }

    public static function esProfesorDelCurso($idProfesorCurso)
    {
        return Auth::check() && Auth::id() === $idProfesorCurso;
    }

    public static function esUsuarioActual($idUsuario)
    {
        return Auth::check() && Auth::id() == $idUsuario;
    }

    public static function estaUsuarioInscritoEnCurso($idCurso)
    {
        return Auth::check() && Auth::user()->cursosEstudiante->contains('id_curso', $idCurso);
    }
}
