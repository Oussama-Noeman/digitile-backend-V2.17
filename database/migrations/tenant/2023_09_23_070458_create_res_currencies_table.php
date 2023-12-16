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
        Schema::create('res_currencies', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->json('symbol');
            $table->integer('decimal_places')->nullable();
            $table->text('full_name')->nullable();
            $table->text('position')->nullable();
            $table->text('currency_unit_label')->nullable();
            $table->text('currency_subunit_label')->nullable();
            $table->double('rounding')->nullable();
            $table->boolean('active')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('res_currencies');
    }
};
