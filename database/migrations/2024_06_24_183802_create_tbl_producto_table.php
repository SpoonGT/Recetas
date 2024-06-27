<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_producto', function (Blueprint $table) {
            $table->id();
            $table->boolean('sub_ensmable')->default(true);
            $table->boolean('activo')->default(true);

            $table->bigInteger('informacion_id')->unsigned()->index();
            $table->foreign('informacion_id')->references('id')->on('tbl_informacion');

            $table->softDeletes();
            $table->string('deleted_by', 25)->nullable();

            $table->unique('informacion_id');
            $table->index(array('informacion_id', 'activo', 'sub_ensmable'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_producto');
    }
}
