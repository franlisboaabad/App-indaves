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
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actividad_id')->constrained()->onDelete('cascade');
            $table->string('nombre_tarea');
            $table->date('fecha_inicio');
            $table->date('fecha_presentacion');
            $table->string('responsable');
            $table->text('sustento_de_trabajo')->nullable();
            $table->enum('estado_de_tarea', ['no iniciado', 'en proceso', 'en revisión', 'culminado']);
            $table->text('comentario')->nullable();
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
        Schema::dropIfExists('tareas');
    }
};
