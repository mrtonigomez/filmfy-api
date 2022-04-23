<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsProductorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items-productors', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('productor_id');

            $table->foreign('item_id')
                ->references('id')
                ->on('items')
                ->onDelete('cascade');
            $table->foreign('productor_id')
                ->references('id')
                ->on('productors')
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
        Schema::dropIfExists('items-productors');
    }
}
