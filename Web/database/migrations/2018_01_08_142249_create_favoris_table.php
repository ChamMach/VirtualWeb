<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFavorisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favoris', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_utilisateur')->unsigned();
            $table->foreign('id_utilisateur')->references('id')->on('users')->onDelete('cascade');
            $table->integer('id_vm')->unsigned();
            $table->foreign('id_vm')->references('id_vm')->on('vm')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favoris');
    }
}
