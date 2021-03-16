<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubservicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subservices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title',1000);
            $table->string('description',1500);
            $table->unsignedBigInteger('icon');
            $table->unsignedBigInteger('service_id');
            $table->boolean('trashed')->default(0);
            $table->foreign('icon')->references('id')->on('media');
            $table->foreign('service_id')->references('id')->on('services');
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
        Schema::dropIfExists('subservices');
    }
}
