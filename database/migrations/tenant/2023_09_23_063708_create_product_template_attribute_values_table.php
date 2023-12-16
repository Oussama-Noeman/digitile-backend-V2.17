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
        Schema::create('product_template_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('p_a_value_id');
            $table->unsignedBigInteger('a_l_id');
            $table->unsignedBigInteger('product_tmpl_id')->nullable();
            $table->unsignedBigInteger('attribute_id')->nullable();
            $table->integer('color')->nullable();
            $table->double('price_extra')->nullable();
            $table->boolean('ptav_active')->nullable();
            $table->timestamps();
            $table->unique(['a_l_id', 'p_a_value_id']);
            $table->index('p_a_value_id');
            $table->index('a_l_id');
            $table->index('product_tmpl_id');
            $table->index('attribute_id');

           

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_template_attribute_values');
    }
};
