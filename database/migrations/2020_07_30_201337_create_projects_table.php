<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('description',500);
            $table->string('client');
            $table->string('cd');
            $table->string('date');
            $table->json('gallery');
            $table->unsignedBigInteger('ft_img');
            $table->unsignedBigInteger('bg_img');
            $table->string('facebook');
            $table->string('twitter');
            $table->string('linkedin');
            $table->string('youtube');
            $table->string('instagram');
            $table->boolean('trashed',0)->default(0);
            $table->foreign('ft_img')->references('id')->on('media');
            $table->foreign('bg_img')->references('id')->on('media');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
