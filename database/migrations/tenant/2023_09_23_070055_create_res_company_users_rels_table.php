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
        Schema::create('res_company_users_rels', function (Blueprint $table) {
           
            $table->unsignedBigInteger('cid');
            $table->unsignedBigInteger('uid');
            $table->primary(['cid', 'uid']);
            $table->timestamps();
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('res_company_users_rels');
    }
};
