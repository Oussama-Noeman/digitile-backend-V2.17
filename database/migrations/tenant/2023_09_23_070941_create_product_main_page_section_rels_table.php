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
        Schema::create('product_main_page_section_rels', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('main_page_section_id');

            $table->primary(['product_id', 'main_page_section_id']);
            $table->index(['main_page_section_id', 'product_id'], 'product_main_page_section_rel_main_page_section_id_product__idx');

           
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_main_page_section_rels');
    }
};
