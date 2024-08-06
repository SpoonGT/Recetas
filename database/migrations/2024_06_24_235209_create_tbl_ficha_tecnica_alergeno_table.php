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

            $table->bigInteger('materia_prima_id')->unsigned()->index();
            $table->foreign('materia_prima_id')->references('id')->on('tbl_materia_prima');

            $table->bigInteger('alergeno_id')->unsigned()->index();
            $table->foreign('alergeno_id')->references('id')->on('tbl_alergeno');

            $table->bigInteger('receta_id')->unsigned()->index();
            $table->foreign('receta_id')->references('id')->on('tbl_receta');

            $table->timestamp('created_at', 0);
            $table->string('created_by', 25);
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
