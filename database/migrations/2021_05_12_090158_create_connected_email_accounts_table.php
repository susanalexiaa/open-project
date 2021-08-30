<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConnectedEmailAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('connected_email_accounts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('name');
            $table->bigInteger('operator_id')->unsigned();
            $table->string('login');
            $table->string('password');
            $table->boolean('is_active');
            $table->timestamp('last_integration')->nullable();
            $table->timestamps();
        });

        Schema::table('connected_email_accounts', function (Blueprint $table) { 
            $table->foreign('operator_id')
                ->references('id')->on('operators')
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
        Schema::dropIfExists('connected_email_accounts');
    }
}
