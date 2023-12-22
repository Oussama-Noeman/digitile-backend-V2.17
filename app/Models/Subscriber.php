<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Stancl\Tenancy\Database\Models\Domain;

class Subscriber extends Model
{
    use HasFactory, notifiable;

    public $fillable = [
        'name', 'name_ar',
        'email',
        'phone',
        'business_name',
        'tenant_id',
        'subscription_id',
        'email_verified_at',
        'password',
        'status',
        'adress', 'adress_ar',

    ];

    protected $hidden = [
        'password'
    ];

    const SUBSCRIBER_ACCOUNT_STATUS = [
        0 => 'Not Active',
        1 => 'Active',
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subscriber) {
            $subscriber->password = Hash::make($subscriber->password);
        });
    }
    //    public function subscriberbranchs()
    //    {
    //        return $this->hasMany(SubscriberBranch::class);
    //    }



    public function tenant(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Tenant::class);
    }

    public function domains(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Domain::class, Tenant::class);
    }
}
