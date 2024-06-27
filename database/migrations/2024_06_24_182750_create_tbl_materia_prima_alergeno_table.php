<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblMateriaPrimaAlergenoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_materia_prima_alergeno', function (Blueprint $table) {
            $table->boolean('activo')->default(true);

            $table->bigInteger('materia_prima_id')->unsigned()->index();
            $table->foreign('materia_prima_id')->references('id')->on('tbl_materia_prima');

            $table->bigInteger('informacion_id')->unsigned()->index();
            $table->foreign('informacion_id')->references('id')->on('tbl_informacion');

            $table->bigInteger('alergeno_id')->unsigned()->index();
            $table->foreign('alergeno_id')->references('id')->on('tbl_alergeno');

            $table->timestamps();
            $table->softDeletes();

            $table->string('created_by', 25);
            $table->string('updated_by', 25)->nullable();
            $table->string('deleted_by', 25)->nullable();

            $table->unique(array('materia_prima_id', 'alergeno_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_materia_prima_alergeno');
    }
}
