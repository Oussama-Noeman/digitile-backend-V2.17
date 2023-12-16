<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverChatImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'sequence',
        'driver_chat_id',
        'image_attachment',
    ];

    public function driverChat()
    {
        return $this->belongsTo(DriverChat::class, 'driver_chat_id', 'id');
    }
}
