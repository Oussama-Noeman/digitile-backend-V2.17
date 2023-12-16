<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTag extends Model
{
    use HasFactory;
    protected $table = 'product_tags';
    protected $fillable = [
        'color',
        'name',
        'website_id',
        'ribbon_id',
    ];
    protected $casts = [
        'name' => 'json',
    ];
    public function templates()
    {
        return $this->belongsToMany(ProductTemplate::class, 'product_tag_product_template_rels', 'product_tag_id', 'product_template_id');
    }
    public function productProducts()
    {
        return $this->belongsToMany(ProductProduct::class, 'product_tag_product_product_rels', 'product_tag_id', 'product_product_id');
    }
}
