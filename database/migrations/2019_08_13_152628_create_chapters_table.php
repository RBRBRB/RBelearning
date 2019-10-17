<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChaptersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chapters', function (Blueprint $table) {
          $table->increments('chapter_id');
          $table->string('chapter');
          $table->string('parent_crouse');
          $table->string('owner');
          //$table->unsignedInteger('subject_id');
          //$table->foreign('subject_id')->references('subject_id')->on('crouses');
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
        Schema::dropIfExists('chapters');
    }
}
