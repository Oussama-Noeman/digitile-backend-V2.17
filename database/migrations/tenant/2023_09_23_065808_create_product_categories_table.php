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
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreignId('company_id')->constrained(table: 'res_companies');
            $table->json('name');
            $table->string('complete_name')->nullable();
            $table->string('parent_path')->nullable();
            // $table->integer('removal_strategy_id')->nullable();
            $table->string('packaging_reserve_method')->nullable();
            $table->string('image')->nullable();
            $table->string('banner_image')->nullable();
            $table->boolean('is_grocery')->nullable();
            $table->boolean('is_main')->nullable();
            $table->boolean('is_publish')->nullable();

            $table->index('parent_id', 'product_category_parent_id_index');
            $table->index('parent_path', 'product_category_parent_path_index');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_categories');
    }
};
