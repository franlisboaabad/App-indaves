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
        Schema::create('presentacion_pollos', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion');
            $table->boolean('estado')->default(1);
            $table->decimal('tara')->nullable();
            $table->timestamps();
        });

        Schema::create('tipo_pollos', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion');
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
        Schema::dropIfExists('presentacion_pollos');
        Schema::dropIfExists('tipo_pollos');
    }
};
