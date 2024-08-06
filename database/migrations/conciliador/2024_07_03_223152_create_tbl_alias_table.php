<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblAliasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_alias', function (Blueprint $table) {
            $table->id();
            $table->string('alias', 125);

            $table->bigInteger('punto_venta_id')->unsigned()->index();
            $table->foreign('punto_venta_id')->references('id')->on('tbl_punto_venta');

            $table->bigInteger('plataforma_id')->unsigned()->index();
            $table->foreign('plataforma_id')->references('id')->on('tbl_plataforma');

            $table->timestamps();
            $table->softDeletes();

            $table->string('created_by', 25);
            $table->string('updated_by', 25)->nullable();
            $table->string('deleted_by', 25)->nullable();

            $table->unique(array('punto_venta_id', 'plataforma_id', 'deleted_at'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_alias');
    }
}
