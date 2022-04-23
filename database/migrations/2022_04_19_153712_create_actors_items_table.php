<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActorsItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actors_items', function (Blueprint $table) {
            $table->unsignedBigInteger('items_id');
            $table->unsignedBigInteger('actors_id');
            $table->string('character_name', 50)->nullable();

            $table->foreign('items_id')
                ->references('id')
                ->on('items')
                ->onDelete('cascade');
            $table->foreign('actors_id')
                ->references('id')
                ->on('actors')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items-actors');
    }
}
