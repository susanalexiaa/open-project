<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FeedbackPhotos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback_photos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('feedback_id')->unsigned();
            $table->string('photo');
            $table->timestamps();
        });

        Schema::table('feedback_photos', function (Blueprint $table) { 
            $table->foreign('feedback_id')
                ->references('id')->on('feedbacks')
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
        Schema::dropIfExists('feedback_photos');
    }
}
