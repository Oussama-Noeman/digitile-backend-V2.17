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
        Schema::table('product_wishlists', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
        });
        Schema::table('product_wishlists', function (Blueprint $table) {
            $table->dropForeign(['partner_id']);
        });

        // Schema::table('product_wishlists', function (Blueprint $table) {
        //     $table->dropColumn('partner_id');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_wishlists', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
};
