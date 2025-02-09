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

            $table->bigInteger('informacion_id')->unsigned()->index();
            $table->foreign('informacion_id')->references('id')->on('tbl_informacion');

            $table->bigInteger('alergeno_id')->unsigned()->index();
            $table->foreign('alergeno_id')->references('id')->on('tbl_alergeno');

            $table->bigInteger('receta_id')->unsigned()->index();
            $table->foreign('receta_id')->references('id')->on('tbl_receta');

            $table->softDeletes();
            $table->string('deleted_by', 25)->nullable();
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
