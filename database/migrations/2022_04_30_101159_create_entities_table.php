<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->date('formdate')->nullable();
            $table->unsignedInteger('roles_id')->index();
            $table->boolean('status')->default(1);
            $table->string("image")->nullable();
            $table->unsignedInteger('country_id')->nullable()->index();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            $table->foreign('country_id')
                ->references('id')
                ->on('countries')
                ->onDelete('cascade');
            $table->foreign('roles_id')
                ->references('id')
                ->on('roles')
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
        Schema::dropIfExists('entities');
    }
}
