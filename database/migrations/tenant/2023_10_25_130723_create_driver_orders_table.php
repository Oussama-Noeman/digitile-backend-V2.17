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
        Schema::create('driver_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained(table:"res_partners");
            $table->foreignId('order_id')->constrained(table:"sale_orders");
            $table->double('latitude', 10, 6);
            $table->double('longitude', 10, 6);
            $table->string('location');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_orders');
    }
};
