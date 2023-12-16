<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteFaq extends Model
{
    use HasFactory;
    protected $table = 'website_faqs';
    protected $fillable = [
        'name',
        'answer',
        'banner',
        'company_id'
    ];

    public function company()
    {
        return $this->belongsTo(ResCompany::class, 'company_id', 'id');
    }
}
