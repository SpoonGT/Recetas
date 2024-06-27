<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblPorcionPesoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_porcion_peso', function (Blueprint $table) {
            $table->bigInteger('porcion_id')->unsigned()->index();
            $table->foreign('porcion_id')->references('id')->on('tbl_porcion');

            $table->bigInteger('cantidad');
            $table->string('nomenclatura', 20);

            $table->bigInteger('unidad_id')->unsigned()->index();
            $table->foreign('unidad_id')->references('id')->on('tbl_unidad');

            $table->timestamps();

            $table->string('created_by', 25);
            $table->string('updated_by', 25)->nullable();

            $table->unique('porcion_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_porcion_peso');
    }
}
