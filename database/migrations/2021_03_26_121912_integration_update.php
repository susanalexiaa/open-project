<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IntegrationUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('integrations', function (Blueprint $table) {
            $table->bigInteger('team_id')->unsigned()->default(1)->after('allowed_addresses');
            $table->bigInteger('responsible_id')->unsigned()->default(1)->after('team_id');
        });

        Schema::table('integrations', function (Blueprint $table) {
            $table->foreign('team_id')
                ->references('id')->on('teams')
                ->onDelete('cascade');
        });

        Schema::table('integrations', function (Blueprint $table) {
            $table->foreign('responsible_id')
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
        Schema::table('integrations', function (Blueprint $table) {
            //
        });
    }
}
