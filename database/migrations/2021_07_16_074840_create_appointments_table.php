<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('user_id');
            $table->text('title');
            $table->string('objective')->nullable();
            $table->dateTime('datetime')->nullable();
            $table->double('latitude', 15, 8)->nullable()->default(0);
            $table->double('longitude', 15, 8)->nullable()->default(0);
            $table->dateTime('checkin_time')->nullable();
            $table->text('comments')->nullable();
            $table->unsignedBigInteger('team_id');
            $table->string('cancel_reason')->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}
