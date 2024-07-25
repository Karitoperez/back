<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
/*             $table->integer('id_docente');
            $table->integer('id_categoria'); */
            $table->string('titulo');
            $table->string('imagen');
            $table->integer('duracion');
            $table->boolean('estado');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->text('descripcion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
