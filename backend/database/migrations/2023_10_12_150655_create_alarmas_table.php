<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alarmas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('componente_id');
            $table->foreign('componente_id')->references('id')->on('componentes');

            $table->unsignedBigInteger('proceso_id');
            $table->foreign('proceso_id')->references('id')->on('procesos');


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
        Schema::dropIfExists('alarmas');
    }
};
