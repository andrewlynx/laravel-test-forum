<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThreadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thread', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 30);
            $table->string('content');
            $table->unsignedInteger('author');
            $table->integer('parent')->default(0);
            $table->timestamps();
            $table->foreign('author')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('threads');
    }
}