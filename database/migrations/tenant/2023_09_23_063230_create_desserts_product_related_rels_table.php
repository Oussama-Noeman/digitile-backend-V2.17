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
        Schema::create('desserts_product_related_rels', function (Blueprint $table) {
            $table->unsignedBigInteger('product_template_id');
            $table->unsignedBigInteger('product_product_id');
            $table->primary(['product_template_id', 'product_product_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desserts_product_related_rels');
    }
};
