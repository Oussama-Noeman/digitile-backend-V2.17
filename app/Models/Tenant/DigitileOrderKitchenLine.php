<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigitileOrderKitchenLine extends Model
{

    use HasFactory;
    protected $fillable = [
        'product_id',
        'model_id',
        'order_kitchen_id',
        'name',
        'state',
        'model_type',
        'notes',
        'order_status',
        'qtity',
    ];
}
