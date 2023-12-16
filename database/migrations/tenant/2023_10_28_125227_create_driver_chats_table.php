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
        Schema::create('driver_chats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->longText('message')->nullable();
            $table->string('image_found')->nullable();
            $table->unsignedBigInteger('driver_user_id')->nullable();
            $table->unsignedBigInteger('client_user_id')->nullable();
            $table->timestamps();
        });

        Schema::table('driver_chats', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('sale_orders')->onDelete('set null');
            $table->foreign('driver_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('client_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_chats');
    }
};
