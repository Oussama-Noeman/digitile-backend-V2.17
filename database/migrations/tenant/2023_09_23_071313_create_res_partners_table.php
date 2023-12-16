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
        Schema::create('res_partners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();

            $table->string('name')->nullable();

            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('kitchen_id')->nullable();


            $table->string('display_name')->nullable();
            $table->string('ref')->nullable();
            $table->string('lang')->nullable();

            $table->string('street')->nullable();

            $table->string('city')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();


            $table->double('partner_latitude')->nullable();
            $table->double('partner_longitude')->nullable();
            $table->boolean('active')->nullable();

            $table->index('name', 'res_partner_name_index');
            $table->index('display_name', 'res_partner_display_name_index');
            $table->string('team_image_attachment')->nullable();
            $table->index('parent_id', 'res_partner_parent_id_index');
            $table->index('kitchen_id', 'res_partner_kitchen_id_index');


            $table->index('company_id', 'res_partner_company_id_index');



            $table->boolean('is_client')->default(false)->nullable();
            $table->boolean('is_driver')->default(false)->nullable();
            $table->boolean('is_member')->default(false)->nullable();
            $table->boolean('is_main')->default(false)->nullable();
            $table->boolean('is_chef')->default(false)->nullable();
            $table->boolean('is_manager')->default(false)->nullable();
            $table->string('position')->nullable();



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('res_partners');
    }
};
