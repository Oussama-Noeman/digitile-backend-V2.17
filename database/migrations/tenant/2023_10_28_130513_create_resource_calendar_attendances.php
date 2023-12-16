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
        Schema::create('resource_calendar_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calendar_id')->constrained(table: "resource_calendars");
            // $table->unsignedInteger('resource_id')->nullable();
            // $table->unsignedInteger('sequence')->nullable();
            $table->string('name');
            $table->string('dayofweek');
            $table->string('day_period');
            $table->string('week_type')->nullable();
            $table->string('display_type')->nullable();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->string('hour_from');
            $table->string('hour_to');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_calendar_attendances');
    }
};
