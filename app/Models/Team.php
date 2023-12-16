<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'team_image_attachment',
        'company_id',
        'create_uid',
        'write_uid',
        'trial146',
    ];

    /**
     * Define a many-to-one relationship with the "res_companies" table.
     * A Team entry belongs to one company.
     */
    public function company()
    {
        return $this->belongsTo(ResCompany::class, 'company_id');
    }
    public function members()
    {
        return $this->belongsToMany(Member::class,"team_members","team_id" , "member_id");
    }
}
