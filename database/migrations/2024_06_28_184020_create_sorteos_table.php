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
        Schema::create('sorteos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_sorteo');
            $table->datetime('fecha_de_sorteo');
            $table->string('premios');
            $table->text('descripcion_del_sorteo')->nullable();
            $table->integer('cantidad_tickets');
            $table->integer('cantidad_vendida')->default(0)->nullable();
            $table->integer('opciones');
            $table->boolean('estado')->default(1);
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
        Schema::dropIfExists('sorteos');
    }
};
