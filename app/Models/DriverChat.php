<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverChat extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'message',
        'image_found',
        'driver_user_id',
        'client_user_id',
    ];

    public function order()
    {
        return $this->belongsTo(SaleOrder::class, 'order_id', 'id');
    }
    public function image()
    {
        return $this->hasMany(DriverChatImage::class, 'driver_chat_id', 'id');
    }
    public function clientUser()
    {
        return $this->belongsTo(User::class, 'client_user_id', 'id');
    }
    public function drivertUser()
    {
        return $this->belongsTo(User::class, 'driver_user_id', 'id');
    }
}
