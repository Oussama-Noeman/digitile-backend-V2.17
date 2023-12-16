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
        Schema::create('sale_order_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->integer('sequence')->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->unsignedBigInteger('order_partner_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('state')->nullable();
            $table->string('display_type')->nullable();
            $table->string('name');
            $table->double('product_uom_qty');
            $table->double('price_unit');
            $table->double('discount')->nullable();
            $table->double('price_total')->nullable();
            $table->double('price_reduce_taxexcl')->nullable();
            $table->double('price_reduce_taxinc')->nullable();
            $table->double('qty_delivered')->nullable();
            $table->double('qty_invoiced')->nullable();
            $table->double('qty_to_invoice')->nullable();
            $table->double('untaxed_amount_invoiced')->nullable();
            $table->double('untaxed_amount_to_invoice')->nullable();
            $table->double('price_tax')->nullable();
            $table->string('order_status')->nullable();
            $table->json('addons_note')->nullable();
            $table->json('removable_ingredients_note')->nullable();
            $table->string('notes')->nullable();
            $table->string('note_addons')->nullable();
            $table->integer('tax')->nullable();
            $table->index('order_id');
            $table->index('currency_id');
            $table->index('order_partner_id');
            $table->index('product_id');
            $table->timestamps();

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_order_lines');
    }
};
