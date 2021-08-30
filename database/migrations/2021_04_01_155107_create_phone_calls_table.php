<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhoneCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phone_calls', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->bigInteger('contractor_id')->unsigned();
            $table->string('link');
            $table->boolean('is_success');
            $table->timestamps();
        });

        Schema::table('phone_calls', function (Blueprint $table) {
            $table->foreign('contractor_id')
                    ->references('id')->on('contractors')
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
        Schema::dropIfExists('phone_calls');
    }
}
