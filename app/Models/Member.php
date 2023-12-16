<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    protected $table = 'members';
    protected $fillable = [
        'fname',
        'lname',
        'member_image_attachment'
        
    ];
    public function teams()
    {
        return $this->belongsToMany(Team::class,"team_members", 'member_id',"team_id");
    }
}
