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
    Schema::create('links', function (Blueprint $table) {
        $table->id();

        $table->unsignedBigInteger('nodo_from_id');
        $table->foreign('nodo_from_id')
              ->references('id')
              ->on('nodos')
              ->onDelete('cascade'); // Configura la eliminación en cascada

        $table->unsignedBigInteger('nodo_to_id');
        $table->foreign('nodo_to_id')
              ->references('id')
              ->on('nodos')
              ->onDelete('cascade'); // Configura la eliminación en cascada

        $table->unsignedBigInteger('etapa_id');
        $table->foreign('etapa_id')->references('id')->on('etapas');

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
        Schema::dropIfExists('links');
    }
};
