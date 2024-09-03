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
            $table->unsignedBigInteger('cliente_id');
            $table->string('serie_venta');
            $table->date('fecha_venta');
            $table->double('peso_neto',8,2)->nullable();
            $table->string('forma_de_pago');
            $table->unsignedBigInteger('metodo_pago_id');
            $table->double('monto_total');
            $table->decimal('monto_recibido', 8, 2)->nullable(); // Monto recibido
            $table->decimal('saldo', 8, 2)->default(0); // Saldo pendiente
            $table->boolean('pagada')->default(false); // Estado de la venta

            $table->string('url_venta_documento_a4')->nullable();
            $table->string('url_venta_documento_ticket')->nullable();
            $table->boolean('estado')->default(1);

            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('metodo_pago_id')->references('id')->on('metodo_pagos');
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
