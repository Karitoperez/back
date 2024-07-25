<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //relaciones:
        Schema::table('users', function (Blueprint $table) {
            // Agregar una nueva clave foránea
            $table->foreignId('id_rol')->constrained("roles");
            $table->foreignId('id_tipo_documento')->constrained('tipo_documentos');
        });

        Schema::table('comentarios', function (Blueprint $table) {
            // Agregar una nueva clave foránea
            $table->foreignId('id_usuario')->constrained('users');
        });

        Schema::table('evaluaciones', function (Blueprint $table) {
            // Agregar una nueva clave foránea
            $table->foreignId('id_docente')->constrained('users');
            $table->foreignId('id_curso')->constrained('cursos');
        });
        Schema::table('cursos', function (Blueprint $table) {
            // Agregar una nueva clave foránea
            $table->foreignId('id_docente')->constrained('users');
            $table->foreignId('id_categoria')->constrained('categorias');
        });
        Schema::table('notas_estudiantes', function (Blueprint $table) {
            // Agregar una nueva clave foránea
            $table->foreignId('id_estudiante')->constrained('users');
            $table->foreignId('id_evaluacion')->constrained('evaluaciones');
        });

        Schema::table('matriculas', function (Blueprint $table) {
            // Agregar una nueva clave foránea
            $table->foreignId('id_estudiante')->constrained('users');
            $table->foreignId('id_curso')->constrained('cursos');
        });
        Schema::table('curso_estudiante', function (Blueprint $table) {
            // Agregar una nueva clave foránea
            $table->foreignId('id_estudiante')->constrained('users');
            $table->foreignId('id_curso')->constrained('cursos');
        });
        Schema::table('archivos_leccion', function (Blueprint $table) {
            // Agregar una nueva clave foránea
            $table->foreignId('id_leccion')->constrained('lecciones');
        });
        Schema::table('lecciones', function (Blueprint $table) {
            // Agregar una nueva clave foránea
            $table->foreignId('id_docente')->constrained('users');
            $table->foreignId('id_curso')->constrained('cursos');
        });
        Schema::table('mensajes', function (Blueprint $table) {
            // Agregar una nueva clave foránea
            $table->foreignId('id_conversacion')->constrained('conversaciones');
            $table->foreignId('id_usuario')->constrained('users');
        });
        Schema::table('participantes_conversacion', function (Blueprint $table) {
            // Agregar una nueva clave foránea
            $table->foreignId('id_conversacion')->constrained('conversaciones');
            $table->foreignId('id_usuario')->constrained('users');
        });
        Schema::table('conversaciones', function (Blueprint $table) {
            // Agregar una nueva clave foránea
            $table->foreignId('id_tipo_conversacion')->constrained('tipos_conversacion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
};
