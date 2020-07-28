<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('designation');
            $table->unsignedBigInteger('media_id');
            $table->integer('order')->default(100000);
            $table->string('facebook',500)->nullable();
            $table->string('twitter',500)->nullable();
            $table->string('instagram',500)->nullable();
            $table->string('linkedin',500)->nullable();
            $table->string('youtube',500)->nullable();
            $table->boolean('trashed')->default(0);
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
        Schema::dropIfExists('team_members');
    }
}
