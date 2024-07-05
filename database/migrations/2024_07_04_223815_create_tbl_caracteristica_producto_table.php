<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblCaracteristicaProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_caracteristica_producto', function (Blueprint $table) {
            $table->bigInteger('ficha_tecnica_id')->unsigned()->index();
            $table->foreign('ficha_tecnica_id')->references('id')->on('tbl_ficha_tecnica');

            $table->string('color', 250);
            $table->string('sabor', 250);
            $table->string('olor', 250);
            $table->string('textura', 250);
            $table->string('altura', 250);
            $table->string('diametro', 250);
            $table->string('largo', 250);
            $table->string('ancho', 250);

            $table->timestamp('created_at', 0);
            $table->string('created_by', 25);

            $table->unique('ficha_tecnica_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_caracteristica_producto');
    }
}
