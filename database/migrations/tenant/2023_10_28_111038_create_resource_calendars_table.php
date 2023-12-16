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
        Schema::create('resource_calendars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained(table: "res_companies")->nullable();
            $table->string('name');
            $table->string('tz');
            $table->boolean('active')->nullable();
            $table->boolean('two_weeks_calendar')->nullable();
            $table->double('hours_per_day')->nullable();
            $table->boolean('is_working_day')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_calendars');
    }
};
