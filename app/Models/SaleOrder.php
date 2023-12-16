<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleOrder extends Model
{
    use HasFactory;

    protected $table = 'sale_orders';
    protected $fillable = [
        'order_time_to_be_ready',
        'assign_time_time',
        'driver_id',
        'company_id',
        'partner_id',
        'partner_invoice_id',
        'partner_shipping_id',
        'fiscal_position_id',
        'driver_id',
        'pricelist_id',
        'currency_id',
        'user_id',
        'name',
        'state',
        'client_order_ref',
        'origin',
        'reference',
        'invoice_status',
        'validity_date',
        'note',
        'currency_rate',
        'amount_untaxed',
        'amount_tax',
        'amount_total',
        'commitment_date',
        'date_order',
        'is_confirmed',
        'total_qty',
        'sale_order_type_id',
        'order_status',
        'delivery_date',
        'zone_id'
    ];

    public function currency()
    {
        return $this->belongsTo(ResCurrency::class, 'currency_id');
    }

    public function productPriceList()
    {
        return $this->belongsTo(ProductPriceList::class, 'pricelist_id');
    }
    public function saleOrderLines()
    {
        return $this->hasMany(SaleOrderLine::class, 'order_id');
    }
    public function saleOrderTypes()
    {
        return $this->belongsTo(SaleOrderType::class, 'sale_order_type_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function partnerInvoice()
    {
        return $this->belongsTo(ResPartner::class, 'partner_invoice_id');
    }
    public function partnerShipping()
    {
        return $this->belongsTo(ResPartner::class, 'partner_shipping_id');
    }
    public function partner()
    {
        return $this->belongsTo(ResPartner::class, 'partner_id');
    }
    public function resPartnerShipping()
    {
        return $this->belongsTo(ResPartner::class, 'partner_shipping_id');
    }
    public function resPartnerInvoice()
    {
        return $this->belongsTo(ResPartner::class, 'partner_invoice_id');
    }
    public function resCompany()
    {
        return $this->belongsTo(ResCompany::class, 'company_id');
    }
    public function driverOrder()
    {
        return $this->hasMany(DriverOrder::class, 'order_id');
    }
    public function driver()
    {
        return $this->belongsTo(ResPartner::class, 'driver_id');
    }
    public function zone()
    {
        return $this->belongsTo(ZoneZone::class, 'zone_id');
    }
}
