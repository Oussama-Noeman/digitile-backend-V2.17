<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUsSlider extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'about_us_slider_image_attachment',
        'company_id',
        'create_uid',
        'write_uid',
        'trial146',
    ];
    
    /**
     * Define a many-to-one relationship with the "res_company" table.
     * An AboutUsSlider entry belongs to one company.
     */
    public function company()
    {
        return $this->belongsTo(ResCompany::class, 'company_id');
    }
}
