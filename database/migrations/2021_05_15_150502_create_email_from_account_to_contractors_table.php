<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailFromAccountToContractorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_from_account_to_contractors', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->bigInteger('contractor_id')->unsigned();
            $table->string('title');
            $table->text('content');
            $table->timestamp('made_at');
            $table->timestamps();
        });

        Schema::table('email_from_account_to_contractors', function (Blueprint $table) {
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
        Schema::dropIfExists('email_from_account_to_contractors');
    }
}
