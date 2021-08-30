<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNomenclatureProductNomenclaturePropertyValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nomenclature_product_nomenclature_property_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nomenclature_product_id');
            $table->unsignedBigInteger('nomenclature_property_value_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nomenclature_product_nomenclature_property_values');
    }
}
