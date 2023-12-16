<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeProductTemplateRels extends Model
{
    use HasFactory;
    protected $table = 'product_attribute_product_template_rels';
    protected $fillable = [
        'product_attribute_id',
        'product_template_id'
    ];
}
