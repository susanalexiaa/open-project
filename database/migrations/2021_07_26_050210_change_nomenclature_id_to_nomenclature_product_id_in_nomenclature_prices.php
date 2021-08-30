<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeNomenclatureIdToNomenclatureProductIdInNomenclaturePrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nomenclature_prices', function (Blueprint $table) {
            $table->dropColumn('nomenclature_id');
            $table->unsignedBigInteger('nomenclature_product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nomenclature_prices', function (Blueprint $table) {
            $table->unsignedBigInteger('nomenclature_id');
            $table->dropColumn('nomenclature_product_id');
        });
    }
}
