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
        Schema::create('product_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->integer('sequence')->nullable();
            $table->unsignedBigInteger('attribute_id');
            $table->integer('color')->nullable();
            $table->string('html_color')->nullable();
            $table->json('name');
            $table->string('image')->nullable();
            $table->boolean('is_custom')->nullable();
            $table->timestamps();
            $table->index('attribute_id');
         
           
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_attribute_values');
    }
};
