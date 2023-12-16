<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrApplication extends Model
{
    use HasFactory;
    protected $fillable = [
        'job_id',
        'name',
        'email_from',
        'partner_name',
        'description',
        'file',
        'partner_mobile'
    ];
    public function hrJob()
    {
        return $this->belongsTo(HrJob::class, 'job_id');
    }
}
