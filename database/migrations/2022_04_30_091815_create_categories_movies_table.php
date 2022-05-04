<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories_movies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('movies_id');
            $table->unsignedBigInteger('categories_id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            $table->foreign('categories_id')
                ->references('id')
                ->on('categories')
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
        Schema::dropIfExists('categories_movies');
    }
}
