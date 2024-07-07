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

            $table->string('codigo', 50)->unique();

            $table->string('prefijo', 10)->default('ESP');
            $table->enum('proceso', ['RE', 'PT']);

            $table->string('abreviatura', 25);
            $table->bigInteger('area_id')->unsigned()->index();
            $table->foreign('area_id')->references('id')->on('tbl_area');

            $table->smallInteger('correlativo');

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
