<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResPartner extends Model
{
    protected $table = 'res_partners';

    protected $fillable = [
        'team_image_attachment',
        'company_id',
        'name',
        'parent_id',
        'user_id',
        'display_name',
        'ref',
        'lang',
        'street',
        'city',
        'email',
        'phone',
        'mobile',
        'partner_latitude',
        'partner_longitude',
        'active',
        'is_client',
        'is_driver',
        'is_member',
        'is_main',
        'position',
        'street2',
        'type',
        'is_chef',
        'is_manager',
        'kitchen_id'
    ];

    use HasFactory;

    // public function productWishlists()
    // {
    //     return $this->hasMany(ProductWishlist::class, 'partner_id');
    // }
    // public function orderTrips()
    // {
    //     return $this->hasMany(OrdersTrip::class, 'driver_id');
    // }
    // // public function tripWizards()
    // // {
    // //     return $this->hasMany(TripWizard::class, 'partner_id', 'id');
    // // }

    // public function saleOrders()
    // {
    //     return $this->hasMany(SaleOrder::class, 'partner_id', 'id');
    // }
    // public function saleOrderShippings()
    // {
    //     return $this->hasMany(SaleOrder::class, 'partner_shipping_id');
    // }
    // public function saleOrderInvoices()
    // {
    //     return $this->hasMany(SaleOrder::class, 'partner_invoice_id');
    // }
    // public function saleOrderLines()
    // {
    //     return $this->hasMany(SaleOrderLine::class, 'order_partner_id');
    // }
    // public function partnerInvoice()
    // {
    //     return $this->hasMany(SaleOrderLine::class, 'partner_invoice_id');
    // }
    // public function partnerShipping()
    // {
    // //     return $this->hasMany(SaleOrderLine::class, 'partner_shipping_id');
    // }

    public function commercialPartner()
    {
        return $this->belongsTo(ResPartner::class, 'commercial_partner_id', 'id');
    }
    public function parent()
    {
        return $this->belongsTo(ResPartner::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(ResPartner::class, 'parent_id', 'id');
    }
    // public function kitchen()
    // {
    //     return $this->belongsTo(DigitileKitchen::class, 'kitchen_id', 'id');
    // }

    public function company()
    {
        return $this->belongsTo(ResCompany::class, 'company_id', 'id');
    }
    public function company1()
    {
        return $this->hasMany(ResCompany::class, 'company_id', 'id');
    }
    public function users()
    {
        return $this->hasMany(User::class, 'partner_id');
    }

    // public function driverOrder()
    // {
    //     return $this->hasMany(DriverOrder::class, 'order_id');
    // }

    // public function driverSaleOrders()
    // {
    //     return $this->hasMany(SaleOrder::class, 'driver_id');
    // }


}
