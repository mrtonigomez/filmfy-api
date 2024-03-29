<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('movies_id')->index();
            $table->unsignedInteger('users_id')->index();
            $table->string('title', 75);
            $table->string('body', 1000);
            $table->integer('rating');
            $table->boolean('moderated')->default(1);
            $table->boolean('status')->default(1);
            $table->integer('likes');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            $table->foreign('movies_id')
                ->references('id')
                ->on('movies')
                ->onDelete('cascade');
            $table->foreign('users_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('comments');
    }
}
