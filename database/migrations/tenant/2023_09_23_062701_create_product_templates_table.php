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
        Schema::create('product_templates', function (Blueprint $table) {
            $table->id();
            $table->integer('sequence')->nullable();
            $table->unsignedBigInteger('categ_id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('type')->nullable();
            $table->string('default_code')->nullable();
            $table->json('name');
            $table->json('description')->nullable();
            $table->double('list_price')->nullable();
            $table->boolean('sale_ok')->default(true);
            $table->boolean('active')->default(true);
            $table->boolean('app_publish')->default(true);
            // $table->double('discount');
            $table->unsignedBigInteger('kitchen_id')->nullable();
            $table->integer('preparing_time')->nullable();
            $table->boolean('is_add_ons')->default(false);
            $table->boolean('is_ingredient')->default(false);
            $table->boolean('is_delivery')->default(false);
            $table->unsignedBigInteger('default_drink_id')->nullable();
            $table->unsignedBigInteger('default_sides_id')->nullable();
            $table->json('drinks_caption')->nullable();
            $table->json('sides_caption')->nullable();
            $table->json('related_caption')->nullable();
            $table->json('liked_caption')->nullable();
            $table->json('desserts_caption')->nullable();
            $table->boolean('drinks_mendatory')->default(false);
            $table->boolean('sides_mendatory')->default(false);
            $table->boolean('is_combo')->default(false);
            $table->index('categ_id');
            $table->index('company_id');
            $table->index('default_drink_id');
            $table->index('default_sides_id');
            $table->index('kitchen_id');
            $table->string('image')->nullable();
            $table->timestamps(); 
            // $table->index('base_unit_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_templates');
    }
};
