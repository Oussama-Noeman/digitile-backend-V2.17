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
        Schema::create('product_pricelists', function (Blueprint $table) {
            $table->id();
            $table->integer('sequence')->nullable();
            $table->unsignedBigInteger('currency_id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('discount_policy');
            $table->json('name');
            $table->boolean('active')->nullable();
            // $table->unsignedBigInteger('website_id')->nullable();
            $table->string('code')->nullable();
            $table->boolean('selectable')->nullable();
            $table->boolean('is_published')->nullable();
            $table->boolean('is_promotion')->nullable();
            $table->boolean('is_banner')->nullable();
            $table->boolean('is_offer')->nullable();
            $table->string('image')->nullable();
            
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_pricelists');
    }
};
