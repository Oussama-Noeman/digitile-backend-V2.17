<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigitileKitchen extends Model
{
    use HasFactory;
    protected $table = 'digitile_kitchens';
    protected $fillable = [
        'name',
        'company_id',
        'is_default',
        'image',
    ];

    public function productTemplates()
    {
        return $this->hasMany(ProductTemplate::class, 'kitchen_id');
    }
    public function company()
    {
        return $this->belongsTo(ResCompany::class, 'company_id');
    }
    public function chef()
    {
        return $this->hasMany(ResPartner::class, 'partner_id');
    }
}
