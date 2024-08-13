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
        Schema::create('registros', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('sorteo_id');
            $table->foreign('sorteo_id')->references('id')->on('sorteos')->onDelete('cascade');
            $table->string('numero_identidad');
            $table->string('nombre_apellidos');
            $table->string('celular');
            $table->string('email');
            $table->double('monto',8,2);
            $table->string('image');
            $table->boolean('estado_registro')->default(0);
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
        Schema::dropIfExists('registros');
    }
};
