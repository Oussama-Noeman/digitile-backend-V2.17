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
        Schema::create('hr_applications', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('campaign_id')->nullable();
            // $table->unsignedBigInteger('source_id')->nullable();
            // $table->unsignedBigInteger('medium_id')->nullable();
            // $table->unsignedBigInteger('message_main_attachment_id')->nullable();
            // $table->unsignedBigInteger('partner_id')->nullable();
            // $table->unsignedBigInteger('stage_id')->nullable();
            // $table->unsignedBigInteger('last_stage_id')->nullable();
            // $table->unsignedBigInteger('company_id')->nullable();
            // $table->unsignedBigInteger('user_id')->nullable();
            $table->foreignId('job_id')->constrained(table: 'hr_jobs')->nullable();
            // $table->unsignedBigInteger('type_id')->nullable();
            // $table->unsignedBigInteger('department_id')->nullable();
            // $table->integer('color')->nullable();
            // $table->unsignedBigInteger('emp_id')->nullable();
            // $table->unsignedBigInteger('refuse_reason_id')->nullable();
            // $table->longText('email_cc')->nullable();
            $table->string('name');
            $table->string('email_from')->nullable();
            // $table->longText('priority')->nullable();
            // $table->longText('salary_proposed_extra')->nullable();
            // $table->longText('salary_expected_extra')->nullable();
            $table->string('partner_name')->nullable();
            // $table->string('partner_phone', 32)->nullable();
            // $table->string('partner_mobile', 32)->nullable();
            // $table->longText('kanban_state');
            // $table->longText('linkedin_profile')->nullable();
            // $table->date('availability')->nullable();
            $table->string('description')->nullable();
            $table->string('partner_mobile')->nullable();
            $table->string('file')->nullable();
            $table->timestamps();
            // $table->tinyInteger('active')->nullable();
            // $table->double('probability')->nullable();
            // $table->double('salary_proposed')->nullable();
            // $table->double('salary_expected')->nullable();
            // $table->double('delay_close')->nullable();



            // $table->foreign('campaign_id')->references('id')->on('utm_campaign')->onDelete('set null');
            // $table->foreign('company_id')->references('id')->on('res_company')->onDelete('set null');
            // $table->foreign('department_id')->references('id')->on('hr_department')->onDelete('set null');
            // $table->foreign('emp_id')->references('id')->on('hr_employee')->onDelete('set null');
            // $table->foreign('job_id')->references('id')->on('hr_job')->onDelete('set null');
            // $table->foreign('last_stage_id')->references('id')->on('hr_recruitment_stage')->onDelete('set null');
            // $table->foreign('medium_id')->references('id')->on('utm_medium')->onDelete('set null');
            // $table->foreign('message_main_attachment_id')->references('id')->on('ir_attachment')->onDelete('set null');
            // $table->foreign('partner_id')->references('id')->on('res_partner')->onDelete('set null');
            // $table->foreign('refuse_reason_id')->references('id')->on('hr_applicant_refuse_reason')->onDelete('set null');
            // $table->foreign('source_id')->references('id')->on('utm_source')->onDelete('set null');
            // $table->foreign('stage_id')->references('id')->on('hr_recruitment_stage');
            // $table->foreign('type_id')->references('id')->on('hr_recruitment_degree')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_applications');
    }
};
