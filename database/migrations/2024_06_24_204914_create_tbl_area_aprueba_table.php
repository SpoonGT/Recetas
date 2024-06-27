<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblAreaApruebaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_area_aprueba', function (Blueprint $table) {
            $table->id();
            $table->boolean('aprobado')->default(false);

            $table->bigInteger('receta_id')->unsigned()->index();
            $table->foreign('receta_id')->references('id')->on('tbl_receta');

            $table->bigInteger('area_id')->unsigned()->index();
            $table->foreign('area_id')->references('id')->on('tbl_area');

            $table->timestamps();

            $table->string('created_by', 25);
            $table->string('updated_by', 25)->nullable();

            $table->unique(array('receta_id', 'area_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_area_aprueba');
    }
}
