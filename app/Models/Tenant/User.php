<?php

namespace App\Models\Tenant;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Tenant\ProductWishlist;
use App\Models\Tenant\ResCompany;
use App\Models\Tenant\ResGroup;
use App\Models\Tenant\ResPartner;
use App\Models\Tenant\SaleOrder;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

// class User extends Authenticatable implements HasTenants
class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'company_id',
        'partner_id',
        'active',
        'signature',
        'share',
        'notification_type',
        'livechat_username',
        'image',
        'login',
        'fcm_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',


    ];

    /**
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function wishlist()
    {
        return $this->hasMany(ProductWishlist::class, 'user_id');
    }

    public function partner()
    {
        return $this->belongsTo(ResPartner::class, 'partner_id');
    }

    public function AllowedCompanies()
    {
        return $this->belongsToMany(ResCompany::class, 'res_company_users_rels', 'uid', 'cid');
    }
    public function saleOrders()
    {
        return $this->hasMany(SaleOrder::class);
    }
    public function resGroups()
    {
        return $this->belongsToMany(ResGroup::class, 'res_groups_users_rel', 'uid', 'gid');
    }
    public function defaultCompany()
    {
        return $this->belongsTo(ResCompany::class, 'company_id');
    }
    public function canAccessFilament(): bool
    {
        // return str_ends_with($this->email, '@yourdomain.com');
        return true;
    }
    //Tenancy methods
    // public function getTenants(Panel $panel): Collection
    // {
    //     // dd($this->AllowedCompanies);
    //     return $this->AllowedCompanies;
    // }
    // public function canAccessTenant(Model $tenant): bool
    // {
    //     return $this->AllowedCompanies->contains($tenant);
    // }
}
