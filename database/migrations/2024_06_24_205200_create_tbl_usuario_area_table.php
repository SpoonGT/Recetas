<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblUsuarioAreaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_usuario_area', function (Blueprint $table) {
            $table->bigInteger('usuario_id')->unsigned()->index();
            $table->foreign('usuario_id')->references('id')->on('tbl_usuario');

            $table->bigInteger('area_id')->unsigned()->index();
            $table->foreign('area_id')->references('id')->on('tbl_area');

            $table->timestamps();
            $table->softDeletes();

            $table->string('created_by', 25);
            $table->string('updated_by', 25)->nullable();
            $table->string('deleted_by', 25)->nullable();

            $table->unique(array('usuario_id', 'area_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_usuario_area');
    }
}
