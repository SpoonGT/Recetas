<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblRecetaAreaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_receta_area', function (Blueprint $table) {
            $table->bigInteger('receta_id')->unsigned()->index();
            $table->foreign('receta_id')->references('id')->on('tbl_receta');

            $table->bigInteger('empaque_id')->unsigned()->index();
            $table->foreign('empaque_id')->references('id')->on('tbl_area');

            $table->bigInteger('produce_id')->unsigned()->index();
            $table->foreign('produce_id')->references('id')->on('tbl_area');

            $table->timestamps();

            $table->string('created_by', 25);
            $table->string('updated_by', 25)->nullable();

            $table->unique('receta_id');
            $table->index(array('receta_id', 'empaque_id', 'produce_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_receta_area');
    }
}
