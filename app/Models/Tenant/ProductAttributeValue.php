<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeValue extends Model
{
    use HasFactory;
    protected $casts = [
        'name' => 'json',
    ];
    protected $table = 'product_attribute_values';
    protected $fillable = [
        'sequence',
        'attribute_id',
        'color',
        'html_color',
        'name',
        'is_custom',
    ];

    public function attribute()
    {
        return $this->belongsTo(ProductAttribute::class, 'attribute_id');
    }

    // public function attributeLines()
    // {
    //     return $this->belongsToMany(ProductTemplateAttributeLine::class, 'product_attribute_value_product_template_attribute_line_rel', 'product_attribute_value_id', 'product_template_attribute_line_id');
    // }

    public function productTemplateAttVal()
    {
        return $this->belongsToMany(ProductTemplateAttributeLine::class, 'product_template_attribute_value', 'product_attribute_value_id', 'attribute_line_id');
    }
    public function templates()
    {
        return $this->belongsToMany(ProductTemplate::class, 'product_template_attribute_values', 'p_a_value_id', 'product_tmpl_id');
    }
    // public function templatePivot()
    // {
    //     return $this->hasMany(ProductTemplateAttributeValue::class, 'p_a_value_id');
    // }

    //TJRIB
    public function attributeLines()
    {
        return $this->belongsToMany(ProductTemplateAttributeLine::class, 'product_attribute_value_product_template_attribute_line_rels', 'product_attribute_value_id', 'product_template_attribute_line_id');
    }
}
