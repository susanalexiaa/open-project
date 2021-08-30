<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocalitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('localities', function (Blueprint $table) {
            $table->id();
            $table->string('REGIONCODE', 4)->nullable();
            $table->string('AUTOCODE', 2)->nullable();
            $table->string('AREACODE', 6)->nullable();
            $table->string('CITYCODE', 6)->nullable();
            $table->string('CTARCODE', 6)->nullable();
            $table->string('PLACECODE', 6)->nullable();
            $table->string('PLANCODE', 8)->nullable();
            $table->string('OFFNAME');
            $table->string('POSTALCODE', 12)->nullable();
            $table->string('SHORTNAME', 20);
            $table->tinyInteger('AOLEVEL');
            $table->tinyInteger('ACTSTATUS');
            $table->tinyInteger('LIVESTATUS');
            $table->string('AOGUID', 72);
            $table->string('PARENTGUID', 72)->nullable();
            $table->string('AOID', 72);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('localities');
    }
}
