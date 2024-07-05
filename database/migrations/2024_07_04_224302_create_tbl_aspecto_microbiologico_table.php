<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblAspectoMicrobiologicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_aspecto_microbiologico', function (Blueprint $table) {
            $table->bigInteger('ficha_tecnica_id')->unsigned()->index();
            $table->foreign('ficha_tecnica_id')->references('id')->on('tbl_ficha_tecnica');

            $table->bigInteger('microbiologico_id')->unsigned()->index();
            $table->foreign('microbiologico_id')->references('id')->on('tbl_microbiologico');

            $table->string('informacion', 150);

            $table->timestamp('created_at', 0);
            $table->string('created_by', 25);

            $table->unique(array('ficha_tecnica_id', 'microbiologico_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_aspecto_microbiologico');
    }
}
