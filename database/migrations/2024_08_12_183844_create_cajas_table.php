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
        Schema::create('cajas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Referencia al usuario que creó o es responsable de la caja
            $table->decimal('monto_apertura', 10, 2); // Monto con el que se abre la caja
            $table->date('fecha_apertura'); // Fecha en la que se abrió la caja
            $table->date('fecha_cierre')->nullable(); // Fecha en la que se cerró la caja (puede ser null si está abierta)
            $table->decimal('monto_cierre', 10, 2)->nullable(); // Monto con el que se abre la caja
            $table->boolean('estado_caja')->default(1); // Estado de la caja (por ejemplo: abierta, cerrada)
            $table->boolean('estado')->default(1); // Estado de la caja para eliminaciones
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
        Schema::dropIfExists('cajas');
    }
};
