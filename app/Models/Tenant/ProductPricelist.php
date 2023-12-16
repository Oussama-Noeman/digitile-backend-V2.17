<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPricelist extends Model
{
    use HasFactory;

    protected $table = 'product_pricelists';
    protected $fillable = [
        'sequence',
        'discount_policy',
        'name',
        'active',
        'code',
        'selectable',
        'is_published',
        'is_promotion',
        'is_banner',
        'is_offer',
        'image',
        'currency_id',
        'from_date',
        'to_date'
    ];
    protected $casts = ['name' => 'json'];
    public function currency()
    {
        return $this->belongsTo(ResCurrency::class, 'currency_id');
    }

    public function saleOrders()
    {
        return $this->hasMany(SaleOrder::class, 'pricelist_id');
    }
    public function productWishlists()
    {
        return $this->hasMany(ProductWishlist::class, 'pricelist_id');
    }
    public function productPricelistItems()
    {
        return $this->hasMany(ProductPricelistItem::class, 'pricelist_id');
    }
    public function company()
    {
        return $this->belongsTo(ResCompany::class, 'company_id');
    }
}
