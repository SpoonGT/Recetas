<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblUsuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_usuario', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo', 150);
            $table->string('usuario', 25)->unique();
            $table->string('email', 100)->unique();
            $table->boolean('full_admin')->default(false);

            $table->bigInteger('rol_id')->unsigned()->index();
            $table->foreign('rol_id')->references('id')->on('tbl_rol');

            $table->timestamps();
            $table->softDeletes();

            $table->string('created_by', 25);
            $table->string('updated_by', 25)->nullable();
            $table->string('deleted_by', 25)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_usuario');
    }
}
