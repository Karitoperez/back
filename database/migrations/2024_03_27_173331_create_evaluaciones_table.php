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
        Schema::create('evaluaciones', function (Blueprint $table) {
            $table->id();
/*             $table->integer('id_docente');
            $table->integer('id_curso'); */
            $table->string('tipo')->nullable(false);
            $table->string('titulo')->nullable(false);
            $table->string('descripcion')->nullable(false);
            $table->string('nota_maxima')->nullable(false);
            $table->timestamp('fecha_inicio')->nullable(false);
            $table->timestamp('fecha_fin')->nullable(false);
            $table->boolean('estado')->nullable(false);;
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluaciones');
    }
};
