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
            $table->double('monto_total');
            $table->decimal('monto_recibido', 8, 2)->nullable();
            $table->decimal('saldo', 8, 2)->default(0);
            $table->text('comentario')->nullable();
            $table->boolean('estado')->default(1);
            $table->foreign('cliente_id')->references('id')->on('clientes');
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
