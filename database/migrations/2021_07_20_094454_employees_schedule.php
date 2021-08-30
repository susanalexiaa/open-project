<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EmployeesSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('team_id')->unsigned();
            $table->year('year');
            $table->timestamps();
        });

        Schema::table('employees_schedules', function (Blueprint $table) {
            $table->foreign('team_id')
                ->references('id')->on('teams')
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
        Schema::dropIfExists('employees_schedules');
    }
}
