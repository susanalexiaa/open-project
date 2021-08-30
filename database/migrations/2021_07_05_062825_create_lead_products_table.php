<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('quantity');
            $table->unsignedBigInteger('price')->nullable();
            $table->unsignedBigInteger('discount')->nullable();
            $table->unsignedBigInteger('sum')->nullable();
            $table->unsignedInteger('vat_rate')->nullable();
            $table->unsignedInteger('sum_vat_rate')->nullable();
            $table->unsignedBigInteger('total')->nullable();
            $table->unsignedBigInteger('nomenclature_product_id')->nullable();
            $table->unsignedBigInteger('lead_id');
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
        Schema::dropIfExists('lead_products');
    }
}
