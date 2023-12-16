<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUsMission extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'about_us_slider_image_attachment',
        'company_id',
        'create_uid',
        'write_uid',
        'trial146',
    ];

    public function company()
    {
        return $this->belongsTo(ResCompany::class, 'company_id');
    }
}
