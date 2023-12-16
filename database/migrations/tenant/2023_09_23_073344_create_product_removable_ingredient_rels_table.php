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
        Schema::create('product_removable_ingredient_rels', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('removable_ingredient_id');

            $table->primary(['product_id', 'removable_ingredient_id']);
            // $table->index(['removable_ingredient_id', 'product_id'], 'product_removable_ingredient__removable_ingredient_id_produ_idx');
            
           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_removable_ingredient_rels');
    }
};
