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
        Schema::create('proyectos_asignados', function (Blueprint $table) {
            $table->unsignedBigInteger('id_proyecto');
            $table->string('dni');
            $table->primary(['id_proyecto', 'dni']);
            $table->foreign('id_proyecto')->references('id')->on('proyectos')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('proyecto_asignado');
    }
};
