<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class SeriesCreateSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('series', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('description', 512)->nullable();
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('post_series', function (Blueprint $table) {
            $table->id();
            $table->integer('series_id')->unsigned()->references('id')->on('series')->onDelete('cascade');
            $table->integer('post_id')->unsigned()->references('id')->on('posts')->onDelete('cascade');
            $table->integer('order')->unsigned()->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('series');
    }
}
