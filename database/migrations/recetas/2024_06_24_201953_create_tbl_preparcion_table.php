<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblPreparcionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_preparcion', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('receta_id')->unsigned()->index(); //BackEnd
            $table->foreign('receta_id')->references('id')->on('tbl_receta');

            $table->smallInteger('secuencia'); //Form
            $table->longText('descripcion'); //Form

            $table->timestamp('created_at', 0); //BackEnd
            $table->string('created_by', 25); //BackEnd

            $table->index(array('receta_id', 'secuencia'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_preparcion');
    }
}
