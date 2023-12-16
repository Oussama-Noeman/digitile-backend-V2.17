<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('res_companies', function (Blueprint $table) {
            $table->integer('tax');
            $table->boolean('tax_included')->default(1);
        });
    }

    public function down()
    {
        Schema::table('res_companies', function (Blueprint $table) {
            $table->dropColumn('tax');
            $table->dropColumn('tax_included');
        });
    }
};
