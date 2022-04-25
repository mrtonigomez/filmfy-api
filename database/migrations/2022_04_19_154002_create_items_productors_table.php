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
        Schema::create('items_productors', function (Blueprint $table) {
            $table->unsignedBigInteger('items_id');
            $table->unsignedBigInteger('productors_id');

            $table->foreign('items_id')
                ->references('id')
                ->on('items')
                ->onDelete('cascade');
            $table->foreign('productors_id')
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
        Schema::dropIfExists('items_productors');
    }
}
