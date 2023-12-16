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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('login')->unique();
            // extra fields
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('partner_id')->nullable()->unique();
            $table->boolean('active')->default(true);
            $table->string('signature')->nullable();
            $table->boolean('share')->nullable();
            $table->string('notification_type')->nullable();
            $table->string('livechat_username')->nullable();
            $table->index('partner_id', 'res_users_partner_id_index');
            $table->string('image')->nullable();
            // end of extra fields
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
