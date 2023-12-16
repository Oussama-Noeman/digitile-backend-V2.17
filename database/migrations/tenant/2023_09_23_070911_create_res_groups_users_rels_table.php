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
        Schema::create('res_groups_users_rels', function (Blueprint $table) {
            $table->unsignedBigInteger('gid');
            $table->unsignedBigInteger('uid');
            $table->primary(['gid', 'uid']);
          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('res_groups_users_rels');
    }
};
