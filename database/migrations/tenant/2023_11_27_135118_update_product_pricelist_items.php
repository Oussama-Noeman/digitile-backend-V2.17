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
        Schema::table('product_pricelist_items', function (Blueprint $table) {
            $table->string('base')->nullable()->change();
            $table->string('compute_price')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_pricelist_items', function (Blueprint $table) {
            $table->string('base')->nullable()->change();
            $table->string('compute_price')->nullable()->change();
        });
    }
};
