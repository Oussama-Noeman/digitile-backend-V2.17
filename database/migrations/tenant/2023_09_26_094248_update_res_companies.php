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
        Schema::table('res_companies', function (Blueprint $table){
            $table->foreign('partner_id')->references('id')->on('res_partners')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('res_currencies')->onDelete('cascade');
        } );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
