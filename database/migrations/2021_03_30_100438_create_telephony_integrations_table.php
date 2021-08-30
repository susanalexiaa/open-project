<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelephonyIntegrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telephony_integrations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('key');
            $table->string('secret');
            $table->bigInteger('team_id')->unsigned()->default(1);
            $table->bigInteger('responsible_id')->unsigned()->default(1);
            $table->boolean('is_active');
            $table->timestamp('last_integration')->nullable();
            $table->timestamps();
        });

        Schema::table('telephony_integrations', function (Blueprint $table) {
            $table->foreign('team_id')
                ->references('id')->on('teams')
                ->onDelete('cascade');
        });

        Schema::table('telephony_integrations', function (Blueprint $table) {
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
        Schema::dropIfExists('telephony_integrations');
    }
}
