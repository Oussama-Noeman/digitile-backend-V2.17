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
        Schema::create('main_page_sections', function (Blueprint $table) {
            $table->id();
            $table->string('section_number');
            $table->unsignedBigInteger('company_id');
            $table->json('name');
            $table->string('image');
            $table->timestamps();
     
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_page_sections');
    }
};
