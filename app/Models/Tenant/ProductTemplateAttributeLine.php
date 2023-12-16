<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTemplateAttributeLine extends Model
{
    use HasFactory;
    protected $table = 'product_template_attribute_lines';
    protected $fillable = [
        'product_tmpl_id',
        'attribute_id',
        'value_count',
        'active',
    ];

    // public function productTemplate()
    // {
    //     return $this->belongsTo(ProductTemplate::class, 'product_tmpl_id');
    // }
    // public function attribute()
    // {
    //     return $this->belongsTo(ProductAttribute::class, 'attribute_id');
    // }

    // public function attributeValues()
    // {
    //     return $this->belongsToMany(ProductAttributeValue::class, 'product_attribute_value_product_template_attribute_line_rel', 'product_template_attribute_line_id', 'product_attribute_value_id');
    // }

    public function valuesOfAttributes()
    {
        return $this->belongsToMany(ProductAttributeValue::class, 'product_attribute_value_product_template_attribute_line_rels', 'product_template_attribute_line_id', 'product_attribute_value_id');
    }
    public function templates()
    {
        return $this->belongsTo(ProductTemplate::class, 'product_tmpl_id');
    }
}
