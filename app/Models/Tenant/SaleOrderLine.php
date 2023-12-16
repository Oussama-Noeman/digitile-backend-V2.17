<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleOrderLine extends Model
{
    use HasFactory;

    protected $table = 'sale_order_lines';

    protected $fillable = [
        'order_id',
        'sequence',
        'currency_id',
        'order_partner_id',
        'product_id',
        'state',
        'display_type',
        'name',
        'product_uom_qty',
        'price_unit',
        'discount',
        'price_total',
        'price_reduce_taxexcl',
        'price_reduce_taxinc',
        'qty_delivered',
        'qty_invoiced',
        'qty_to_invoice',
        'untaxed_amount_invoiced',
        'untaxed_amount_to_invoice',
        'price_tax',
        'order_status',
        'addons_note',
        'removable_ingredients_note',
        'notes',
        'note_addons',
        'notes_removable_ingredients',
        'tax',
    ];

    public function currency()
    {
        return $this->belongsTo(ResCurrency::class, 'currency_id');
    }

    public function productTemplateAttributeValues()
    {
        return $this->belongsToMany(ProductTemplateAttributeValue::class, 'product_template_attribute_value_sale_order_line_rels', 's_o_l_id', 'p_t_a_value_id');
    }
    public function product()
    {
        return $this->belongsTo(ProductProduct::class, 'product_id');
    }
    public function saleOrder()
    {
        return $this->belongsTo(SaleOrder::class, 'order_id');
    }
    public function partner()
    {
        return $this->belongsTo(ResPartner::class, 'order_partner_id');
    }
    public function saleOrderLineImage()
    {
        return $this->hasMany(SaleOrderLineImage::class, 'order_line_id');
    }
}
