<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'about_us_banner_attachment',
        'image_link_attachment',
        'company_id',
        'create_uid',
        'write_uid',
        'links',
        'video_url',
        'description',
        'create_date',
        'write_date',
        'trial139',
    ];
    
    public function company()
    {
        return $this->belongsTo(ResCompany::class, 'company_id');
    }
}
