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
        Schema::table('hr_jobs', function (Blueprint $table) {
            $table->boolean('is_cv')->default(false)->nullable()->change();
            $table->boolean('active')->default(true)->nullable()->change();
            $table->boolean('is_published')->default(false)->nullable()->change()   ;
            $table->longText('description')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hr_jobs', function (Blueprint $table) {
            $table->boolean('is_cv')->default(false)->nullable()->change();
            $table->boolean('active')->default(true)->nullable()->change();
            $table->boolean('is_published')->default(false)->nullable()->change();
            $table->dropColumn('description')->nullable();

        });
    }
};
