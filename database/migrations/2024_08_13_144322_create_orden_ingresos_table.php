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
        Schema::create('orden_ingresos', function (Blueprint $table) {
            $table->id();
            $table->string('numero_guia');
            $table->integer('cantidad_jabas');
            $table->integer('cantidad_pollos');
            $table->integer('cantidad_pollos_stock');
            $table->decimal('peso_total', 8, 2); // Peso total en kilogramos (o la unidad que prefieras)
            $table->boolean('estado')->default(true);
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
        Schema::dropIfExists('orden_ingresos');
    }
};
