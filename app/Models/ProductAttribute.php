<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;
    protected $table = 'product_attributes';
    protected $casts = [
        'name' => 'json',
    ];
    protected $fillable = [
        'sequence',
        'create_variant',
        'display_type',
        'name',
        'visibility',
    ];

    // public function templates()
    // {
    //     return $this->belongsToMany(ProductTemplate::class, 'product_attribute_product_template_rels', 'product_attribute_id', 'product_template_id');
    // }
    public function attributeValues()
    {
        return $this->hasMany(ProductTemplateAttributeValue::class, 'attribute_id');
    }

    //raje3a ba3den 
    // public function templates()
    // {
    //     return $this->belongsToMany(ProductTemplate::class,'product_template_attribute_lines','attribute_id','product_tmpl_id');
    // }
    // public function templates()
    // {
    //     return $this->belongsToMany(ProductTemplate::class,'product_template_attribute_values','attribute_id','product_tmpl_id');
    // }
    public function attributeLines()
    {
        return $this->belongsToMany(ProductTemplate::class, `product_template_attribute_lines`, 'attribute_id', 'product_tmpl_id');
    }
    public function values()
    {
        return $this->hasMany(ProductAttributeValue::class, 'attribute_id');
    }
    public function templateAttributeValues()
    {
        return $this->belongsToMany(ProductTemplateAttributeValue::class, 'intermediate_attr_tmpl_values', 'attribute_id', 'ptav_id');
    }
}
