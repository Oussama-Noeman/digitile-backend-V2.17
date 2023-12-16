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
        Schema::create('product_wishlists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('partner_id')->nullable();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('pricelist_id')->nullable();
            $table->unsignedBigInteger('product_template_id')->nullable();
            $table->double('price')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->unique(['product_id', 'partner_id']);
            $table->index('partner_id');
            $table->index('pricelist_id');
            $table->index('product_template_id');
            // $table->index('website_id');

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_wishlists');
    }
};
