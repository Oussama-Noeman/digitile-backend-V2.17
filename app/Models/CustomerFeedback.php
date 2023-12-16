<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerFeedback extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'customer_feedback_image_attachment',
        'company_id',
        'create_uid',
        'write_uid',
        'customer_comment',
        'trial146',
    ];

    /**
     * Define a many-to-one relationship with the "res_companies" table.
     * A CustomerFeedback entry belongs to one company.
     */
    protected $casts = [
        "customer_comment" => "json",
        ];
    public function company()
    {
        return $this->belongsTo(ResCompany::class, 'company_id');
    }
}
