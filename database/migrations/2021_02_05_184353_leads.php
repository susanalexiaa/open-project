<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Leads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('fullname')->nullable();
            $table->bigInteger('source_id')->unsigned();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->bigInteger('status_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('leads', function (Blueprint $table) { 
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });

        Schema::table('leads', function (Blueprint $table) { 
            $table->foreign('status_id')
                ->references('id')->on('lead_statuses')
                ->onDelete('cascade');
        });

        Schema::table('leads', function (Blueprint $table) { 
            $table->foreign('source_id')
                ->references('id')->on('lead_sources')
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
        Schema::dropIfExists('leads');
    }
}
