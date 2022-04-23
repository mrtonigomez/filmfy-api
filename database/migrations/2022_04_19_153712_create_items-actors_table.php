<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsActorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items-actors', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('actor_id');
            $table->string('character_name', 50);

            $table->foreign('item_id')
                ->references('id')
                ->on('items')
                ->onDelete('cascade');
            $table->foreign('actor_id')
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
