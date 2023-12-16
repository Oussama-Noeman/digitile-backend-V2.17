<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainBanner3 extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'company_id',
        'description',
        'banner_url',
        'image',
    ];
    public function company()
    {
        return $this->belongsTo(ResCompany::class, 'company_id');
    }
}
