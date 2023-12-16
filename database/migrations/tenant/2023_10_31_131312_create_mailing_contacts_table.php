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
        Schema::create('mailing_contacts', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('message_main_attachment_id')->nullable();
            // $table->unsignedBigInteger('message_bounce')->nullable();
            // $table->unsignedBigInteger('title_id')->nullable();
            // $table->unsignedBigInteger('country_id')->nullable();
             $table->unsignedBigInteger('company_id')->nullable();
            $table->string('email_normalized');
            $table->string('name');
            $table->string('email');
            $table->timestamps();

            // $table->foreign('country_id')->references('id')->on('res_country')->onUpdate('cascade')->onDelete('set null');
            // $table->foreign('create_uid')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
            // $table->foreign('message_main_attachment_id')->references('id')->on('ir_attachment')->onUpdate('cascade')->onDelete('set null');
            // $table->foreign('title_id')->references('id')->on('res_partner_title')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('company_id')->references('id')->on('res_companies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mailing_contacts');
    }
};
