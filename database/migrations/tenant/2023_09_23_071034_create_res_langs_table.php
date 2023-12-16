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
        Schema::create('res_langs', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('code');
            $table->string('iso_code')->nullable();
            $table->string('url_code');
            $table->string('direction');
            $table->string('date_format');
            $table->string('time_format');
            $table->string('week_start');
            $table->string('grouping');
            $table->string('decimal_point');
            $table->string('thousands_sep')->nullable();
            $table->boolean('active')->nullable();
            // $table->unique('name', 'res_lang_name_uniq');
            // $table->unique('code', 'res_lang_code_uniq');
            // $table->unique('url_code', 'res_lang_url_code_uniq');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('res_langs');
    }
};
