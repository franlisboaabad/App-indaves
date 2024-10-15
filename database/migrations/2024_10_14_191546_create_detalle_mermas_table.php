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
        Schema::create('detalle_mermas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merma_id')->constrained('mermas')->onDelete('cascade');
            $table->string('presentacion');
            $table->string('tipo');
            $table->decimal('peso');
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
        Schema::dropIfExists('detalle_mermas');
    }
};
