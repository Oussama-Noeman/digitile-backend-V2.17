<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainPageSection extends Model
{   
    use HasFactory;

    protected $casts = [
        "name" => "json"
    ];
    protected $fillable = [
        'section_number',
        'company_id',
        'name',
        'image',
    ];
    public function company()
    {
        return $this->belongsTo(ResCompany::class,'company_id');
    }

    public function products()
  {
    return $this->belongsToMany(ProductTemplate::class, 'product_main_page_section_rels', 'main_page_section_id', 'product_id');
  }

}
