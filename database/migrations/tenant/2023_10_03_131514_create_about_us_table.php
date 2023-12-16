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
        Schema::create('about_us', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('about_us_banner_attachment')->nullable();
            $table->string('image_link_attachment')->nullable();
            $table->longText('links')->nullable();
            $table->longText('video_url')->nullable();
            $table->longText('description')->nullable();
            $table->foreign('company_id')->references('id')->on('res_companies')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_us');
    }
};
