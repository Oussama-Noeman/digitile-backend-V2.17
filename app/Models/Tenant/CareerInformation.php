<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerInformation extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'title',
        'description',
        'icon1',
        'icon2',
        'icon3',
        'title1',
        'title2',
        'title3',
        'description1',
        'description2',
        'description3',
        'vacancies_title',
        'vacancies_description',
        'company_id',
    ];

    public function company()
    {
        return $this->belongsTo(ResCompany::class);
    }
}
