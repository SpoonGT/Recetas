<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblFichaTecnicaAlergenoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ficha_tecnica_alergeno', function (Blueprint $table) {
            $table->bigInteger('ficha_tecnica_id')->unsigned()->index();
            $table->foreign('ficha_tecnica_id')->references('id')->on('tbl_ficha_tecnica');

            $table->bigInteger('alergeno_id')->unsigned()->index();
            $table->foreign('alergeno_id')->references('id')->on('tbl_alergeno');

            $table->unique(array('ficha_tecnica_id', 'alergeno_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_ficha_tecnica_alergeno');
    }
}
