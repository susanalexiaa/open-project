<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePhoneCallsSystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('call_id_and_caller_ids', function (Blueprint $table) {
            $table->string('type')->after('callerId');
        });

        Schema::table('phone_calls', function (Blueprint $table) {
            $table->dropColumn('is_success');
            $table->string('disposition')->after('type');
            $table->string('link')->nullable()->change();
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
