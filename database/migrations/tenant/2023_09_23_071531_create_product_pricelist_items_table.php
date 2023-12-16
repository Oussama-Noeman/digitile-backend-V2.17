<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_pricelist_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pricelist_id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->unsignedBigInteger('categ_id')->nullable();
            $table->unsignedBigInteger('product_tmpl_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('base_pricelist_id')->nullable();
            $table->string('applied_on');
            $table->string('base');
            $table->string('compute_price');
            $table->double('min_quantity')->nullable();
            $table->double('fixed_price')->nullable();
            $table->double('price_discount')->nullable();
            $table->double('price_round')->nullable();
            $table->double('price_surcharge')->nullable();
            $table->double('price_min_margin')->nullable();
            $table->double('price_max_margin')->nullable();
            $table->boolean('active')->nullable();
            $table->dateTime('date_start')->nullable();
            $table->dateTime('date_end')->nullable();
            $table->double('percent_price')->nullable();
            
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_pricelist_items');
    }
};
