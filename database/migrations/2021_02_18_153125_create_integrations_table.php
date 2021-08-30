<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntegrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->bigInteger('operator_id')->unsigned();
            $table->string('login');
            $table->string('password');
            $table->text('allowed_addresses');
            $table->boolean('is_active');
            $table->timestamp('last_integration')->nullable();
            $table->timestamps();
        });

        Schema::table('integrations', function (Blueprint $table) { 
            $table->foreign('operator_id')
                ->references('id')->on('operators')
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
        Schema::dropIfExists('integrations');
    }
}
