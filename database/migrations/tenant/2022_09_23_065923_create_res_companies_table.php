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
        Schema::create('res_companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('partner_id');
            $table->unsignedBigInteger('currency_id');
            $table->integer('sequence')->nullable();
            $table->integer('parent_id')->nullable();
            $table->string('image')->nullable();

            $table->string('city')->nullable();
            $table->string('area')->nullable();
            $table->string('street')->nullable();
            $table->string('near')->nullable();
            $table->string('bulding')->nullable();
            $table->integer('floor')->nullable();

            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();

            $table->string('company_details')->nullable();
            $table->boolean('active')->nullable();
            $table->boolean('is_main')->default(true);
            $table->integer('resource_calendar_id')->nullable();
            $table->integer('hr_presence_control_email_amount')->nullable();
            $table->string('hr_presence_control_ip_list')->nullable();

            $table->string('fees_type')->nullable();
            $table->double('fixed_fees')->nullable();
            $table->double('minimum_fees')->nullable();
            $table->double('price_by_km')->nullable();

            $table->string('social_twitter')->nullable();
            $table->string('social_facebook')->nullable();
            $table->string('social_github')->nullable();
            $table->string('social_linkedin')->nullable();
            $table->string('social_youtube')->nullable();
            $table->string('social_instagram')->nullable();
            $table->string('whatsapp')->nullable();

            $table->string('terms_and_conditions')->nullable();
            $table->string('privacy_policy')->nullable();
            $table->string('support')->nullable();

            $table->string('category_image_attachment')->nullable();
            $table->string('cart_image_attachment')->nullable();
            $table->string('checkout_image_attachment')->nullable();
            $table->string('deal_banner_image_attachment')->nullable();
            $table->string('deal_background_image_attachment')->nullable();
            $table->string('sign_banner_attachment')->nullable();
            $table->string('category_title')->nullable();
            $table->string('cart_title')->nullable();
            $table->string('checkout_title')->nullable();
            $table->string('deal_title1')->nullable();
            $table->string('deal_title2')->nullable();

            $table->string('faq_banner')->nullable();
            $table->string('career_banner')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('res_companies');
    }
};
