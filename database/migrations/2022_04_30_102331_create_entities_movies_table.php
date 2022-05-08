<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntitiesMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entities_movies', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('entities_id');
            $table->unsignedInteger('movies_id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            $table->foreign('entities_id')
                ->references('id')
                ->on('entities')
                ->onDelete('cascade');
            $table->foreign('movies_id')
                ->references('id')
                ->on('movies')
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
        Schema::dropIfExists('entities_movies');
    }
}
