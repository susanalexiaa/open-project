<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallIdAndCallerIdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('call_id_and_caller_ids', function (Blueprint $table) {
            $table->id();
            $table->string('callIdWithRec');
            $table->string('callerId');
            $table->timestamp('until');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('call_id_and_caller_ids');
    }
}
