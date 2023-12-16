<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeValueProductTemplateAttributeLineRels extends Model
{
    use HasFactory;
    protected $table = 'product_attribute_value_product_template_attribute_line_rels';
    protected $fillable = [
        'product_attribute_value_id',
        'product_template_attribute_line_id'
    ];
}
