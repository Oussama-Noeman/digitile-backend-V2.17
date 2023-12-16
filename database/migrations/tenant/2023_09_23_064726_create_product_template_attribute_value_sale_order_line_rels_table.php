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
        Schema::create('product_template_attribute_value_sale_order_line_rels', function (Blueprint $table) {
            $table->timestamps();
            $table->unsignedBigInteger('s_o_l_id');
            $table->unsignedBigInteger('p_t_a_value_id');

            $table->primary(['s_o_l_id', 'p_t_a_value_id']);
            $table->index(['p_t_a_value_id', 's_o_l_id'], 'pta_vsol_id_idx');

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_template_attribute_value_sale_order_line_rels');
    }
};
