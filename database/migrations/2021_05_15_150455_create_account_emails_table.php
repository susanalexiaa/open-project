<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('account_emails', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('emailuid')->unsigned();
            $table->string('folder');
            $table->string('email');
            $table->timestamps();
        });

        Schema::create('emailuids2', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('emailuid')->unsigned();
            $table->string('email');
            $table->timestamps();
        });


        Artisan::call('db:seed', array('--class' => 'MoveEmailUids'));

        Schema::dropIfExists('emailuids');

        Schema::rename('emailuids2', 'emailuids');


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_emails');
    }
}
