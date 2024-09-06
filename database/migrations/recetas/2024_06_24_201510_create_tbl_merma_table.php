<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblMermaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_merma', function (Blueprint $table) {
            $table->bigInteger('receta_id')->unsigned()->index(); //BackEnd
            $table->foreign('receta_id')->references('id')->on('tbl_receta');

            $table->smallInteger('cantidad'); //BackEnd
            $table->string('nomenclatura', 20); //BackEnd

            $table->bigInteger('unidad_id')->unsigned()->index(); //BackEnd
            $table->foreign('unidad_id')->references('id')->on('tbl_unidad');

            $table->timestamp('created_at', 0); //BackEnd
            $table->string('created_by', 25); //BackEnd

            $table->unique('receta_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_merma');
    }
}