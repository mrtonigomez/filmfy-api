<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rating', function (Blueprint $table) {
            $table->integer('comment_id');
            $table->integer('overall');
            $table->integer('argument');
            $table->integer('actors');
            $table->integer('image');
            $table->integer('sound');
            $table->integer('montage');
            $table->integer('effects');


            $table->foreign('comment_id')
                ->references('id')
                ->on('comment')
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
        Schema::dropIfExists('rating');
    }
}
