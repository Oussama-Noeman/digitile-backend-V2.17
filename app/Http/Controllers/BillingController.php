<?php

namespace App\Http\Controllers;

use App\Models\Tenant\ProductProduct;
use App\Models\Tenant\SaleOrder;
use App\Utils\Lang;
use Illuminate\Http\Request;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;

class BillingController extends Controller
{
    public function getbill(SaleOrder $record)
    {
        // Assuming you have a customer name and email in your SaleOrder model
        $customer = new Buyer([
            'name'          => $record->customer_name,
            'custom_fields' => [
                'email' => $record->customer_email,
            ],
        ]);

        $invoice = Invoice::make()->template('pos')->buyer($customer)->totalAmount($record->amount_total)->taxableAmount($record->amount_untaxed)->taxRate(10);

        foreach ($record->saleOrderLines as $line) {
           
            $product=ProductProduct::find($line->product_id);
         
            $item = InvoiceItem::make("")
                ->title(Lang::get_name($product->name,'en'))
                ->pricePerUnit($line->price_unit)
                ->quantity($line->product_uom_qty);

            $invoice->addItem($item);
        }

        return $invoice->stream();
    }
}
