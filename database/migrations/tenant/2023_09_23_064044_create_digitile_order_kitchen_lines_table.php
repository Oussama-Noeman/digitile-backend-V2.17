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
        Schema::create('digitile_order_kitchen_lines', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->nullable();
            $table->integer('model_id')->nullable();
            $table->integer('order_kitchen_id')->nullable();
            $table->string('name');
            $table->string('state');
            $table->string('model_type')->nullable();
            $table->string('notes')->nullable();
            $table->string('order_status')->nullable();
            $table->double('qtity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digitile_order_kitchen_lines');
    }
};
