<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblPlataformaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_plataforma', function (Blueprint $table) {
            $table->id();
            $table->string('abreviatura', 10)->unique();
            $table->string('plataforma', 125);
            $table->smallInteger('fila');
            $table->boolean('redondea');

            $table->timestamps();
            $table->softDeletes();

            $table->string('created_by', 25);
            $table->string('updated_by', 25)->nullable();
            $table->string('deleted_by', 25)->nullable();

            $table->index(array('abreviatura', 'deleted_at'));
            $table->index(array('plataforma', 'deleted_at'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_plataforma');
    }
}
