<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTemplateAttributeValue extends Model
{
    use HasFactory;
    protected $table = 'product_template_attribute_values';
    protected $fillable = [
        'p_a_value_id',
        'a_l_id',
        'product_tmpl_id',
        'attribute_id',
        'color',
        'price_extra',
        'ptav_active',
        'value_name'
    ];
    // public function productTemplate()
    // {
    //     return $this->belongsTo(ProductTemplate::class, 'product_tmpl_id');
    // }
    public function saleOrderLines()
    {
        return $this->belongsToMany(SaleOrderLine::class, 'product_template_attribute_value_sale_order_line_rels', 'p_t_a_value_id', 's_o_l_id');
    }
    public function productProducts()
    {
        return $this->belongsToMany(ProductProduct::class, 'product_variant_combinations', 'p_t_a_value_id', 'p_p_id');
    }
    public function attribute()
    {
        return $this->belongsTo(ProductAttribute::class, 'attribute_id');
    }
    public function productAttributeLine()
    {
        return $this->belongsTo(ProductTemplateAttributeLine::class, 'a_l_id');
    }
    // public function productAttVal()
    // {
    //     return $this->belongsTo(ProductAttributeValue::class, 'p_a_value_id');
    // }
    public function attributes()
    {
        return $this->belongsToMany(ProductAttribute::class, 'intermediate_attr_tmpl_values', 'ptav_id', 'attribute_id');
    }
}
