<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleOrderType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];

    public function SaleOrder()
    {
        return $this->hasMany(SaleOrder::class, 'sale_order_type_id');
    }
}
