<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTashimlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tashimlogs', function (Blueprint $table) {
            $table->id();
            $table->boolean('type');
            $table->unsignedBigInteger('label_id')->index();
            $table->foreign('label_id')->references('id')->on('labels');
            $table->unsignedBigInteger('from_section_id')->index();
            $table->foreign('from_section_id')->references('id')->on('sections');
            $table->unsignedBigInteger('to_section_id')->index();
            $table->foreign('to_section_id')->references('id')->on('sections');
            $table->unsignedBigInteger('prev_value');
            $table->unsignedBigInteger('receive');
            $table->unsignedBigInteger('send');
            $table->unsignedBigInteger('final');
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
        Schema::dropIfExists('tashimlogs');
    }
}
