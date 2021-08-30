<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Init extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // companies

        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('name');
            $table->string('phone');
        });

        Schema::table('companies', function (Blueprint $table) { 
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });

        // watched object

        Schema::create('entities', function (Blueprint $table) { 
            $table->id();
            $table->bigInteger('company_id')->unsigned();
            $table->string('name');
        });

        Schema::table('entities', function (Blueprint $table) { 
            $table->foreign('company_id')
                ->references('id')->on('companies')
                ->onDelete('cascade');
        });


        // feedbacks
        Schema::create('feedbacks', function (Blueprint $table) { 
            $table->id();
            $table->bigInteger('entity_id')->unsigned();
            $table->string('fullname');
            $table->string('phone');
            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->timestamps();
        });
        
        Schema::table('feedbacks', function (Blueprint $table) { 
            $table->foreign('entity_id')
                ->references('id')->on('entities')
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
        //
    }
}
