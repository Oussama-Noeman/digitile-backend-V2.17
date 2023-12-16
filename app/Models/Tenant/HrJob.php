<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrJob extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id',
        'name',
        'active',
        'image',
        'is_cv',
        'is_published',
        'description'
    ];
    public function company()
    {
        return $this->belongsTo(ResCompany::class, 'company_id');
    }
    public function hrApplicants()
    {
        return $this->hasMany(HrApplication::class, 'job_id');
    }
}
