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
        Schema::table('product_pricelists', function (Blueprint $table) {
            $table->unsignedBigInteger('currency_id')->nullable()->change();
            $table->string('discount_policy')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_pricelists', function (Blueprint $table) {
            $table->unsignedBigInteger('currency_id')->change();
            $table->string('discount_policy')->change();
        });
    }
};
