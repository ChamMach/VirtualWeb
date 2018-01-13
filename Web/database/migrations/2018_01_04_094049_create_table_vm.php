<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableVm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vm', function (Blueprint $table) {
            $table->increments('id_vm');
            $table->integer('id_utilisateur')->unsigned();
            $table->foreign('id_utilisateur')->references('id')->on('users')->onDelete('cascade');
            $table->datetime('date_creation')->default(null)->nullable();
            $table->string('nom');
            $table->string('description');
            $table->string('statut');
            $table->string('os');
            $table->integer('cpu');
            $table->float('ram');
            $table->string('unite_ram');
            $table->float('sto_l');
            $table->string('unite_sto_l');
            $table->float('sto_r');
            $table->string('unite_sto_r');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vm');
    }
}
