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
        Schema::create('lista_precios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('presentacion_pollo_id')->nullable()->constrained('presentacion_pollos');
            $table->foreignId('tipo_pollo_id')->nullable()->constrained('tipo_pollos');
            $table->double('precio',8,2);
            $table->string('descripcion')->nullable();
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
        Schema::dropIfExists('lista_precios');
    }
};
