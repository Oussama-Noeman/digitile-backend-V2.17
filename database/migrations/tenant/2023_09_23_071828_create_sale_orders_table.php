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
        Schema::create('sale_orders', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('campaign_id')->nullable();
            // $table->unsignedBigInteger('source_id')->nullable();
            // $table->unsignedBigInteger('medium_id')->nullable();
            // $table->unsignedBigInteger('message_main_attachment_id')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('partner_id');
            $table->unsignedBigInteger('partner_invoice_id')->nullable();
            $table->unsignedBigInteger('partner_shipping_id');
            $table->unsignedBigInteger('fiscal_position_id')->nullable();
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->unsignedBigInteger('pricelist_id')->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();


            $table->string('name');
            $table->string('state')->nullable();
            $table->string('client_order_ref')->nullable();
            $table->string('origin')->nullable();
            $table->string('reference')->nullable();

            $table->string('invoice_status')->nullable();
            $table->date('validity_date')->nullable();
            $table->string('note')->nullable();
            $table->double('currency_rate')->nullable();
            $table->double('amount_untaxed')->nullable();
            $table->double('amount_tax')->nullable();
            $table->double('amount_total')->nullable();

            $table->datetime('commitment_date')->nullable();
            $table->datetime('date_order');
     



            $table->boolean('is_confirmed')->nullable();
            $table->double('total_qty')->nullable();


            $table->unsignedBigInteger('sale_order_type_id');
            $table->integer('order_status')->nullable();
            $table->datetime('delivery_date')->nullable();
            $table->datetime('assign_time_time')->nullable();
             $table->integer('order_time_to_be_ready')->nullable();


            $table->index(['date_order', 'id'], 'sale_order_date_order_id_idx');
            $table->index(['name'], 'sale_order_name_index');
            $table->index(['company_id'], 'sale_order_company_id_index');
            $table->index(['partner_id'], 'sale_order_partner_id_index');
            $table->index(['state'], 'sale_order_state_index');
            $table->index(['user_id'], 'sale_order_user_id_index');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_orders');
    }
};
