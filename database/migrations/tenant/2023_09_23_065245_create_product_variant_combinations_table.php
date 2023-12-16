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
        Schema::create('product_variant_combinations', function (Blueprint $table) {
            $table->unsignedBigInteger('p_p_id');
            $table->unsignedBigInteger('p_t_a_value_id');

            $table->primary(['p_p_id', 'p_t_a_value_id']);
            $table->index(['p_t_a_value_id', 'p_p_id'],'p_t_a_p_p_idx');

           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variant_combinations');
    }
};
