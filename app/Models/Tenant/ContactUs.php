<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    use HasFactory;
    protected $table = 'contact_us';
    protected $fillable = [
        'name',
        'email',
        'phone',
        'comment',
        'company_id',
    ];

    public function company()
    {
        return $this->belongsTo(ResCompany::class, 'company_id');
    }
}
