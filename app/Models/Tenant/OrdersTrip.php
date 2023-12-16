<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdersTrip extends Model

{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'access_token',
        'name',
        'reference',
        'state',
        'delivered_date',
        'total',
        'delivered_total',
        'rest_total',
    ];
    public function partner()
    {
        return $this->belongsTo(ResPartner::class, 'driver_id');
    }
}
