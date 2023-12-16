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
        Schema::create('res_groups', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            // $table->unsignedBigInteger('category_id')->nullable();
            $table->json('comment')->nullable();
            $table->boolean('share')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('res_groups');
    }
};
