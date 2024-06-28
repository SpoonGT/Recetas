<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblProductoSubTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_producto_sub', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('producto_id')->unsigned()->index();
            $table->foreign('producto_id')->references('id')->on('tbl_producto');

            $table->bigInteger('sub_producto_id')->unsigned()->index();
            $table->foreign('sub_producto_id')->references('id')->on('tbl_producto');

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
        Schema::dropIfExists('tbl_producto_sub');
    }
}
