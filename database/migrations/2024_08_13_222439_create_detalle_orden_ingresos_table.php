<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_orden_ingresos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_ingreso_id')->constrained('orden_ingresos')->onDelete('cascade');
            $table->foreignId('presentacion_pollo_id')->nullable()->constrained('presentacion_pollos');
            $table->foreignId('tipo_pollo_id')->nullable()->constrained('tipo_pollos');
            $table->integer('cantidad_pollos')->nullable();
            $table->integer('cantidad_jabas')->nullable();
            $table->decimal('tara', 8, 2)->nullable();
            $table->decimal('peso_neto', 8, 2)->nullable();
            $table->decimal('peso_promedio', 8, 2)->nullable();
            $table->boolean('estado')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_orden_despachos');
    }
};
