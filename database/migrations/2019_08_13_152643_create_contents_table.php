<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contents', function (Blueprint $table) {
          $table->increments('content_id');
          $table->text('front');
          $table->text('detail')->nullable();
          $table->string('chapter');
          $table->string('crouse');
          $table->string('owner');

          //$table->unsignedInteger('chapter_id');
          //$table->foreign('chapter_id')->references('chapter_id')->on('chapters');
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
        Schema::dropIfExists('contents');
    }
}
