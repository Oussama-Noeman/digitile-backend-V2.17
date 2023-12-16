<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    protected $table = "coupons";
    protected $fillable = [
        "name",
        "from_date",
        "to_date",
        "nbre",
        "discount_type",
        "amount",
    ];
}
