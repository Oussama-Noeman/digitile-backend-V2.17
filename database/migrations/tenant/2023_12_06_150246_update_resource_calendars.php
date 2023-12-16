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
        Schema::table('resource_calendars', function (Blueprint $table) {
        
            $table->string('tz')->nullable()->change();
          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resource_calendars', function (Blueprint $table) {
            $table->string('tz')->nullable(false)->change();
        });
    }
};
