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
        Schema::create('driver_chat_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_chat_id')->nullable();
            $table->integer('image_attachment')->nullable();
            $table->timestamps();
        });

        Schema::table('driver_chat_images', function (Blueprint $table) {
            $table->foreign('driver_chat_id')->references('id')->on('driver_chats')->onDelete('set null');
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_chat_images');
    }
};
