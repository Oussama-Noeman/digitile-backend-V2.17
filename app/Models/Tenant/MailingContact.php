<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailingContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'email_normalized',
        'name',
        'company_id',
        'email',


    ];
    public function company()
    {
        return $this->belongsTo(ResCompany::class, 'company_id');
    }
}
