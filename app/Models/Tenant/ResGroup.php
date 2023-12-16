<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResGroup extends Model
{
    use HasFactory;
    protected $casts = [
        'name' => 'json'
    ];
    protected $fillable = [
        'name',
        'color',
        'comment',
        'share',
    ];
    public function users()
    {
        return $this->belongsToMany(User::class, 'res_groups_users_rel', 'gid', 'uid');
    }
}
