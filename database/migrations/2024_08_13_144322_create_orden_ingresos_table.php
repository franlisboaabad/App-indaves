<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orden_ingresos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('numero_guia');
            $table->decimal('peso_bruto', 8, 2);
            $table->decimal('peso_tara', 8, 2);
            $table->decimal('peso_neto', 8, 2);
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orden_ingresos');
    }
};
