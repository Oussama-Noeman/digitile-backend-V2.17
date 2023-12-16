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
        Schema::create('zone_zones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->string('marker_color')->nullable();
            $table->string('get_map')->nullable();
            $table->string('get_geo_lines')->nullable();
            $table->string('get_drawing')->nullable();
            $table->boolean('show_fee')->nullable();
            $table->double('delivery_fees')->nullable();
            $table->timestamps();

            $table->index('company_id');

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zone_zones');
    }
};
