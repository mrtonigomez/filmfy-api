<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemProductorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item-productor', function (Blueprint $table) {
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('productor_id');

            $table->foreign('item_id')
                ->references('id')
                ->on('item')
                ->onDelete('cascade');
            $table->foreign('productor_id')
                ->references('id')
                ->on('productor')
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
        Schema::dropIfExists('item-productor');
    }
}
