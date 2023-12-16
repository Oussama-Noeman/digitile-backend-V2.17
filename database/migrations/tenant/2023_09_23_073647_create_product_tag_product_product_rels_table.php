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
        Schema::create('product_tag_product_product_rels', function (Blueprint $table) {
            $table->unsignedBigInteger('product_product_id');
            $table->unsignedBigInteger('product_tag_id');

            $table->primary(['product_product_id', 'product_tag_id']);
            
            
       
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_tag_product_product_rels');
    }
};
