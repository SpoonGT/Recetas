<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblHorneoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_horneo', function (Blueprint $table) {
            $table->bigInteger('receta_id')->unsigned()->index(); //BackEnd
            $table->foreign('receta_id')->references('id')->on('tbl_receta');

            $table->string('tiempo_horneo'); //Form
            $table->smallInteger('horno'); //Form

            $table->string('temperatura', 100); //Form

            $table->longText('otros')->nullable(); //Form

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
        Schema::dropIfExists('tbl_horneo');
    }
}
