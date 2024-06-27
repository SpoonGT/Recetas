<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblRolMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_rol_menu', function (Blueprint $table) {
            $table->bigInteger('rol_id')->unsigned()->index();
            $table->foreign('rol_id')->references('id')->on('tbl_rol');

            $table->bigInteger('menu_id')->unsigned()->index();
            $table->foreign('menu_id')->references('id')->on('tbl_menu');

            $table->timestamps();

            $table->string('created_by', 25);
            $table->string('updated_by', 25)->nullable();

            $table->unique(array('rol_id', 'menu_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_rol_menu');
    }
}
