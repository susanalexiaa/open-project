<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RecreateNomenclatureProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('nomenclature_products');
        Schema::create('nomenclature_products', function (Blueprint $table) {
            $table->id();
            $table->engine = 'MyISAM';
            $table->text('name')->nullable();
            $table->unsignedInteger('sort')->default(500);
            $table->unsignedBigInteger('nomenclature_id');
            $table->timestamps();
        });
        DB::statement('ALTER TABLE nomenclature_products ADD FULLTEXT search(name)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nomenclature_products', function (Blueprint $table){
            $table->dropIndex('search');
        });
        Schema::dropIfExists('nomenclature_products');
    }
}
