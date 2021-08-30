<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractorLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contractor_lead', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('lead_id')->unsigned();
            $table->bigInteger('contractor_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('contractor_lead', function (Blueprint $table) { 
            $table->foreign('lead_id')
                ->references('id')->on('leads')
                ->onDelete('cascade');

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
        Schema::dropIfExists('contractor_leads');
    }
}
