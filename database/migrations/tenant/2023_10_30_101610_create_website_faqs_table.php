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

            Schema::create('website_faqs', function (Blueprint $table) {
                $table->id();
                $table->longText('name')->nullable();
                $table->longText('answer')->nullable();
                $table->string('banner')->nullable();
                $table->timestamps();

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_faqs');
    }
};
