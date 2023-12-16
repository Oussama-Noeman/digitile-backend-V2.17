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
        Schema::create('res_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('partner_id');
            $table->boolean('active')->default(1);
            $table->string('login');
            $table->string('password')->nullable();
            $table->unsignedBigInteger('action_id')->nullable();
            $table->string('signature')->nullable();
            $table->boolean('share')->nullable();
            // $table->unsignedBigInteger('user_platform')->nullable();
            $table->string('user_token')->nullable();
            $table->string('totp_secret')->nullable();
            $table->string('notification_type');
            $table->string('odoobot_state')->nullable();
            $table->boolean('odoobot_failed')->nullable();
            // $table->unsignedBigInteger('sale_team_id')->nullable();
            // $table->unsignedBigInteger('website_id')->nullable();
            $table->string('livechat_username')->nullable();
            // $table->unique(['login', 'website_id'], 'res_users_login_key');
            $table->index('partner_id', 'res_users_partner_id_index');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('res_users');
    }
};
