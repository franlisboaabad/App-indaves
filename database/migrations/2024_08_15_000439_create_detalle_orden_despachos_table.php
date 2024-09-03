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
        Schema::create('detalle_orden_despachos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_despacho_id')->constrained('orden_despachos')->onDelete('cascade');
            $table->foreignId('presentacion_pollo_id')->nullable()->constrained('presentacion_pollos');
            $table->foreignId('tipo_pollo_id')->nullable()->constrained('tipo_pollos');
            $table->integer('cantidad_pollos');
            $table->decimal('peso_bruto', 8, 2);
            $table->integer('cantidad_jabas');
            $table->decimal('tara', 8, 2);
            $table->decimal('peso_neto', 8, 2);
            $table->decimal('precio', 8, 2);
            $table->decimal('subtotal', 8, 2);
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
        Schema::dropIfExists('detalle_orden_despachos');
    }
};
