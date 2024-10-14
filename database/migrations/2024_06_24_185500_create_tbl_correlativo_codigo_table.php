<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblCorrelativoCodigoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_correlativo_codigo', function (Blueprint $table) {
            $table->id();

            $table->string('codigo', 50)->unique(); //ESP-RE-DES-175

            $table->string('prefijo', 10)->default('ESP'); //ESP
            $table->enum('proceso', ['RE', 'PT', 'PI']); //RE

            $table->string('abreviatura_area', 25); //DE
            $table->bigInteger('area_id')->unsigned()->index();
            $table->foreign('area_id')->references('id')->on('tbl_area');

            $table->string('abreviatura_marca_comercial', 5); //S
            $table->bigInteger('marca_comercial_id')->unsigned()->index();
            $table->foreign('marca_comercial_id')->references('id')->on('tbl_marca_comercial');

            $table->smallInteger('correlativo'); //175

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
        Schema::dropIfExists('tbl_correlativo_codigo');
    }
}
