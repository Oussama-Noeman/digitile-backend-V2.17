<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreeDelivery extends Model
{
    use HasFactory;
    protected $table = 'free_deliveries';

    protected $fillable = [
        "active",
        "amount",
    ];
}
