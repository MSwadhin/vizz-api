<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slides', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('slider_id');
            $table->unsignedBigInteger('media_id');
            $table->integer('order')->default(100000);
            $table->string('name');
            $table->string('text',500)->nullable();
            $table->foreign('slider_id')->references('id')->on('sliders');
            $table->foreign('media_id')->references('id')->on('media');
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
        Schema::dropIfExists('slides');
    }
}
