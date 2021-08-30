<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkingCellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('working_cells', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('day_id')->unsigned();
            $table->dateTime('start_period');
            $table->boolean('is_busy')->default(0);
        });

        Schema::table('working_cells', function (Blueprint $table) {
            $table->foreign('day_id')
                ->references('id')->on('working_days')
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
        Schema::dropIfExists('working_cells');
    }
}
