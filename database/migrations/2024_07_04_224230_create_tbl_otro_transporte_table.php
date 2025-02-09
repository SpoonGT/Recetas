<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblOtroTransporteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_otro_transporte', function (Blueprint $table) {
            $table->bigInteger('ficha_tecnica_id')->unsigned()->index();
            $table->foreign('ficha_tecnica_id')->references('id')->on('tbl_ficha_tecnica');

            $table->bigInteger('otro_id')->unsigned()->index();
            $table->foreign('otro_id')->references('id')->on('tbl_otro');

            $table->bigInteger('transporte_id')->unsigned()->index();
            $table->foreign('transporte_id')->references('id')->on('tbl_transporte');

            $table->timestamp('created_at', 0);
            $table->string('created_by', 25);

            $table->unique(array('ficha_tecnica_id', 'otro_id', 'transporte_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_otro_transporte');
    }
}
