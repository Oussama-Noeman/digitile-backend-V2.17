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
        Schema::create('product_category_product_product_rels', function (Blueprint $table) {
            $table->unsignedBigInteger('product_category_id');
            $table->unsignedBigInteger('product_product_id');

            $table->primary(['product_category_id', 'product_product_id']);
            $table->index(['product_product_id', 'product_category_id'], 'p_c_p_p_p_p_id_p_c_idx');

       
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_category_product_product_rels');
    }
};
