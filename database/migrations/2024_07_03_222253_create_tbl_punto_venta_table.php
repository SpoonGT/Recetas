<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblPuntoVentaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_punto_venta', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 25)->unique();
            $table->string('local', 125);
            $table->string('alias', 125);

            $table->timestamps();
            $table->softDeletes();

            $table->string('created_by', 25);
            $table->string('updated_by', 25)->nullable();
            $table->string('deleted_by', 25)->nullable();

            $table->unique(array('local', 'alias'));
            $table->index(array('codigo', 'deleted_at'));
            $table->index(array('local', 'alias', 'deleted_at'));
            $table->index(array('alias'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_punto_venta');
    }
}
