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
        Schema::create('tareas_asignadas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_tarea');
            $table->string('dni');
            $table->primary(['id_tarea', 'dni']);
            $table->foreign('id_tarea')->references('id')->on('tareas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('dni')->references('dni')->on('usuarios')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tarea_asignada');
    }
};
