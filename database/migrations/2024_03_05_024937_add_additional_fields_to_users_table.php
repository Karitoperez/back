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
        Schema::table('users', function (Blueprint $table) {
            $table->string('apellido');
            $table->string('numero_documento');
            $table->string('usuario')->unique();
            $table->date('fecha_nacimiento');
            $table->string('direccion');
            $table->string('imagen');
/*             $table->integer('id_rol');
            $table->integer('id_tipo_documento'); */
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
