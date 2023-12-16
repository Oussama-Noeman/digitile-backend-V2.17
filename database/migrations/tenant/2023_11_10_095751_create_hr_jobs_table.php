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
        Schema::create('hr_jobs', function (Blueprint $table) {
            $table->id();
            // $table->integer('message_main_attachment_id')->nullable();
            // $table->integer('sequence')->nullable();
            // $table->integer('expected_employees')->nullable();
            // $table->integer('no_of_employee')->nullable();
            // $table->integer('no_of_recruitment')->nullable();
            // $table->integer('no_of_hired_employee')->nullable();
            // $table->integer('department_id')->nullable();
            $table->foreignId('company_id')->constrained(table: 'res_companies')->nullable();
            // $table->integer('contract_type_id')->nullable();
            // $table->integer('create_uid')->nullable();
            // $table->integer('write_uid')->nullable();
            $table->string('name');
            // $table->longText('description')->nullable();
            // $table->longText('requirements')->nullable();
            $table->tinyInteger('active')->nullable();
            // $table->dateTime('create_date')->nullable();
            // $table->dateTime('write_date')->nullable();
            // $table->integer('alias_id');
            // $table->integer('address_id')->nullable();
            // $table->integer('manager_id')->nullable();
            // $table->integer('user_id')->nullable();
            // $table->integer('hr_responsible_id')->nullable();
            // $table->integer('color')->nullable();
            // $table->integer('website_id')->nullable();
            // $table->longText('website_meta_og_img')->nullable();
            // $table->json('website_meta_title')->nullable();
            // $table->json('website_meta_description')->nullable();
            // $table->json('website_meta_keywords')->nullable();
            // $table->json('seo_name')->nullable();
            // $table->json('website_description')->nullable();
            // $table->json('job_details')->nullable();
            // $table->tinyInteger('is_published')->nullable();
            $table->string('image')->nullable();
            $table->integer('is_cv')->default(false)->nullable();
            $table->integer('is_published')->default(false)->nullable();
            $table->timestamps();
            // $table->char('trial924', 1)->nullable();

            // $table->index('address_id', 'hr_job_address_id_fkey');
            // $table->index('alias_id', 'hr_job_alias_id_fkey');
            // $table->index('company_id', 'hr_job_company_id_fkey');
            // $table->index('contract_type_id', 'hr_job_contract_type_id_fkey');
            // $table->index('create_uid', 'hr_job_create_uid_fkey');
            // $table->index('department_id', 'hr_job_department_id_fkey');
            // $table->index('hr_responsible_id', 'hr_job_hr_responsible_id_fkey');
            // $table->index('image_attachment', 'hr_job_image_attachment_fkey');
            // $table->index('manager_id', 'hr_job_manager_id_fkey');
            // $table->index('message_main_attachment_id', 'hr_job_message_main_attachment_id_fkey');
            // $table->index('user_id', 'hr_job_user_id_fkey');
            // $table->index('website_id', 'hr_job_website_id_fkey');
            // $table->index('write_uid', 'hr_job_write_uid_fkey');

            // $table->foreign('address_id')->references('id')->on('res_partner')->onDelete('set null');
            // $table->foreign('alias_id')->references('id')->on('mail_alias');
            // $table->foreign('company_id')->references('id')->on('res_company')->onDelete('set null');
            // $table->foreign('contract_type_id')->references('id')->on('hr_contract_type')->onDelete('set null');
            // $table->foreign('create_uid')->references('id')->on('res_users')->onDelete('set null');
            // $table->foreign('department_id')->references('id')->on('hr_department')->onDelete('set null');
            // $table->foreign('hr_responsible_id')->references('id')->on('res_users')->onDelete('set null');
            // $table->foreign('image_attachment')->references('id')->on('ir_attachment')->onDelete('set null');
            // $table->foreign('manager_id')->references('id')->on('hr_employee')->onDelete('set null');
            // $table->foreign('message_main_attachment_id')->references('id')->on('ir_attachment')->onDelete('set null');
            // $table->foreign('user_id')->references('id')->on('res_users')->onDelete('set null');
            // $table->foreign('website_id')->references('id')->on('website');
            // $table->foreign('write_uid')->references('id')->on('res_users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_jobs');
    }
};
