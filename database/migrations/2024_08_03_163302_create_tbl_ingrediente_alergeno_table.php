<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblIngredienteAlergenoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ingrediente_alergeno', function (Blueprint $table) {
            $table->bigInteger('receta_id')->unsigned()->index();
            $table->foreign('receta_id')->references('id')->on('tbl_receta');

            $table->bigInteger('ingrediente_id')->unsigned()->index();
            $table->foreign('ingrediente_id')->references('id')->on('tbl_ingrediente');

            $table->bigInteger('informacion_id')->unsigned()->index();
            $table->foreign('informacion_id')->references('id')->on('tbl_informacion');

            $table->bigInteger('alergeno_id')->unsigned()->index();
            $table->foreign('alergeno_id')->references('id')->on('tbl_alergeno');

            $table->index(array('receta_id', 'ingrediente_id'));
            $table->index(array('receta_id', 'ingrediente_id', 'informacion_id', 'alergeno_id'));

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
        Schema::dropIfExists('tbl_ingrediente_alergeno');
    }
}
