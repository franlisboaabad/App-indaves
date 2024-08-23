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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('orden_despacho_id');

            $table->string('serie_venta');
            $table->date('fecha_venta');
            $table->double('peso_neto',8,2);
            $table->string('forma_de_pago');
            $table->unsignedBigInteger('metodo_pago_id');
            $table->double('monto_total');
            $table->string('url_venta_documento_a4')->nullable();
            $table->string('url_venta_documento_ticket')->nullable();
            $table->boolean('estado')->default(1);

            $table->foreign('orden_despacho_id')->references('id')->on('orden_despachos')->onDelete('cascade');
            $table->foreign('metodo_pago_id')->references('id')->on('metodo_pagos')->onDelete('cascade');
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
        Schema::dropIfExists('ventas');
    }
};
