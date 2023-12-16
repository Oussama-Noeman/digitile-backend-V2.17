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
        Schema::create('related_product_rels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained(table: "product_templates");
            $table->foreignId('related_id')->constrained(table: "product_products");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('related_product_rels');
    }
};
