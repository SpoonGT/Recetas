<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_token', function (Blueprint $table) {
            $table->string('token', 250);
            $table->string('token_refresh', 250)->nullable();
            $table->dateTime('expira');

            $table->bigInteger('usuario_id')->unsigned()->index();
            $table->foreign('usuario_id')->references('id')->on('tbl_usuario');

            $table->softDeletes();
            $table->string('deleted_by', 25)->nullable();

            $table->index(array('token', 'usuario_id', 'deleted_by'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_token');
    }
}
