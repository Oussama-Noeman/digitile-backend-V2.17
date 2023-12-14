<?php

namespace App\Models;

use App\Models\Subscriber;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;
    protected $fillable = ['id', 'subscriber_id'];

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'subscriber_id'
        ];
    }

    public function subscriber(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Subscriber::class);
    }
}
