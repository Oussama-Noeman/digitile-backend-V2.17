<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResCurrency extends Model
{
    use HasFactory;
    protected $table = 'res_currencies';
    protected $fillable = [
        'name',
        'symbol',
        'decimal_places',
        'full_name',
        'position',
        'currency_unit_label',
        'currency_subunit_label',
        'rounding',
        'active',
    ];
    protected $casts = [
        "name"=>"json",
        "symbol"=>"json",
    ];
    public function productPricelists()
    {
        return $this->hasMany(ProductPricelist::class, 'currency_id');
    }
    public function saleOrders()
    {
        return $this->hasMany(SaleOrder::class, 'currency_id');
    }
    public function productPricelistItems()
    {
        return $this->hasMany(ProductPricelistItem::class, 'currency_id');
    }
    public function saleOrderLines()
    {
        return $this->hasMany(SaleOrderLine::class, 'currency_id');
    }
}
