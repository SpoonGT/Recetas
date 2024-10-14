<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblRecetaRechazadaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_receta_rechazada', function (Blueprint $table) {
            $table->id();

            $table->boolean('resuelto')->default(false)->index();
            $table->longText('comentario');

            $table->bigInteger('receta_id')->unsigned()->index();
            $table->foreign('receta_id')->references('id')->on('tbl_receta');

            $table->bigInteger('usuario_id')->unsigned()->index();
            $table->foreign('usuario_id')->references('id')->on('tbl_usuario');

            $table->bigInteger('receta_original_id')->default(0);

            $table->timestamp('created_at', 0);
            $table->timestamp('updated_at', 0)->nullable();

            $table->index(array('receta_id', 'resuelto'));
            $table->index(array('usuario_id', 'resuelto'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_receta_rechazada');
    }
}
