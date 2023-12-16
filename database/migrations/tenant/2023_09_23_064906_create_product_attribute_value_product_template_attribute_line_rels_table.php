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
        Schema::create('product_attribute_value_product_template_attribute_line_rels', function (Blueprint $table) {
            $table->unsignedBigInteger('product_attribute_value_id');
            $table->unsignedBigInteger('product_template_attribute_line_id');
            
            $table->primary(['product_attribute_value_id', 'product_template_attribute_line_id']);
            $table->index(['product_template_attribute_line_id', 'product_attribute_value_id'], 'p_a_v_p_p_t_a_li_idx');

            
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_attribute_value_product_template_attribute_line_rels');
    }
};
