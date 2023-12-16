<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigitileOrderKitchen extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'model_id',
        'name',
        'state',
        'model_type',
        'order_status',
        'date_order',
    ];
}
