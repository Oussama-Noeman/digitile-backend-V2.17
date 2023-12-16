<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductWishlist extends Model
{
    use HasFactory;
    protected $table = 'product_wishlists';
    protected $fillable = [
        'user_id',
        'product_id',
        'pricelist_id',
        'price',
        'active',
        'product_template_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function productTemplate()
    {
        return $this->belongsTo(ProductTemplate::class, 'product_template_id');
    }
    public function productProduct()
    {
        return $this->belongsTo(ProductProduct::class, 'product_id');
    }
    public function productPricelist()
    {
        return $this->belongsTo(ProductPricelist::class,'pricelist_id');
    }
    // public function partner()
    // {
    //     return $this->belongsTo(ResPartner::class,'partner_id');
    // }

}
