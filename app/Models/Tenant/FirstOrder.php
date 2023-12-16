<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirstOrder extends Model
{
    use HasFactory;
    protected $table = "first_orders";
    protected $fillable = [
        'type',
        'amount'
    ];
}
