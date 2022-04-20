<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemActorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item-actor', function (Blueprint $table) {
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('actor_id');
            $table->string('character_name', 50);

            $table->foreign('item_id')
                ->references('id')
                ->on('item')
                ->onDelete('cascade');
            $table->foreign('actor_id')
                ->references('id')
                ->on('actor')
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
        Schema::dropIfExists('item-actor');
    }
}
