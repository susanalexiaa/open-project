<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_lead', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('lead_id')->unsigned();
            $table->bigInteger('status_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('history_lead', function (Blueprint $table) { 
            $table->foreign('lead_id')
                ->references('id')->on('leads')
                ->onDelete('cascade');

            $table->foreign('status_id')
                ->references('id')->on('lead_statuses')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')->on('users')
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
        Schema::dropIfExists('history_leads');
    }
}
