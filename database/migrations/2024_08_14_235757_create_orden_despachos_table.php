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
        Schema::create('orden_despachos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained()->onDelete('cascade');
            $table->string('serie_orden');
            $table->date('fecha_despacho');
            $table->integer('cantidad_pollos');
            $table->decimal('peso_total_bruto');
            $table->integer('cantidad_jabas');
            $table->integer('tara');
            $table->decimal('peso_total_neto');
            $table->boolean('estado_despacho')->default(0);
            $table->decimal('subtotal');
            $table->boolean('estado')->default(1);
            $table->boolean('check_beneficiado')->nullable();
            $table->string('type','50')->default('salida');
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
        Schema::dropIfExists('orden_despachos');
    }
};
