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
        Schema::create('orders_trips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id');
            // $table->integer('vehicle_id');
            $table->string('access_token')->nullable();
            $table->string('name');
            $table->string('reference')->nullable();
            $table->string('state')->nullable();
            $table->datetime('delivered_date')->nullable();
            $table->double('total')->nullable();
            $table->double('delivered_total')->nullable();
            $table->double('rest_total')->nullable();
            $table->timestamps();

          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders_trips');
    }
};
