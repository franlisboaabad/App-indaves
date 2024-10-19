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
        Schema::table('inventories', function ( Blueprint $table){
            $table->unsignedSmallInteger('tipo_ingreso')
                ->default(1)
                ->nullable();
        });

        Schema::table('detalle_mermas', function ( Blueprint $table){
            $table->unsignedSmallInteger('tipo_ingreso')
                ->default(1)
                ->nullable();
        });

        Schema::table('orden_ingresos', function ( Blueprint$table){
            $table->unsignedSmallInteger('tipo_ingreso')
                ->default(1)
                ->nullable();
        });
    }

    public function down()
    {
        Schema::table('inventories', function ( Blueprint $table){
            $table->dropColumn('tipo_ingreso');
        });

        Schema::table('detalle_mermas', function (Blueprint $table){
            $table->dropColumn('tipo_ingreso');
        });

        Schema::table('orden_ingresos', function (Blueprint $table){
            $table->dropColumn('tipo_ingreso');
        });
    }
};
