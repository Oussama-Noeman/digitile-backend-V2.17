<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPricelistItem extends Model
{
    use HasFactory;
    protected $table = 'product_pricelist_items';
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
        'product_tmpl_id',
        'applied_on'
    ];

    public function productTemplate()
    {
        return $this->belongsTo(ProductTemplate::class, 'product_tmpl_id');
    }
    public function currency()
    {
        return $this->belongsTo(ResCurrency::class, 'currency_id');
    }
    public function productProduct()
    {
        return $this->belongsTo(ProductProduct::class, 'product_id');
    }
    public function productPricelist()
    {
        return $this->belongsTo(ProductPricelist::class, 'pricelist_id');
    }
    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'categ_id');
    }
    public function company()
    {
        return $this->belongsTo(ResCompany::class, 'company_id');
    }
}
