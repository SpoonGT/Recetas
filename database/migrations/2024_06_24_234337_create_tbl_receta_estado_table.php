<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblRecetaEstadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_receta_estado', function (Blueprint $table) {
            $table->bigInteger('estado_id')->unsigned()->index();
            $table->foreign('estado_id')->references('id')->on('tbl_estado');

            $table->bigInteger('receta_id')->unsigned()->index();
            $table->foreign('receta_id')->references('id')->on('tbl_receta');

            $table->timestamp('created_at', 0);
            $table->string('created_by', 25);

            $table->index(array('estado_id', 'receta_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_receta_estado');
    }
}
