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
        Schema::create('detalle_ventas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('venta_id');
            $table->unsignedBigInteger('orden_despacho_id');
            $table->integer('cantidad_pollos');
            $table->double('peso_bruto',8,2);
            $table->integer('cantidad_jabas');
            $table->double('tara',8,2);
            $table->double('peso_neto');
            $table->boolean('estado')->default(1);
            $table->foreign('venta_id')->references('id')->on('ventas')->onDelete('cascade');
            $table->foreign('orden_despacho_id')->references('id')->on('orden_despachos')->onDelete('cascade');

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
        Schema::dropIfExists('detalle_ventas');
    }
};
