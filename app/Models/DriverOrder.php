<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        "driver_id",
        "order_id",
        "latitude",
        "longitude",
        "location",
    ];
    public function resPartner()
    {
        return $this->belongsTo(ResPartner::class,'driver_id');
    }
    public function saleOrder()
    {
        return $this->belongsTo(SaleOrder::class,'order_id');
    }
    
}
