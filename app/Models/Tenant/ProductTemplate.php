<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTemplate extends Model
{
    use HasFactory;

    protected $table = 'product_templates';
    protected $casts = [
        "name" => "json",
        "description" => "json",
        'list_price' => 'float',
        'sale_ok' => 'boolean',
        'active' => 'boolean',
        "drinks_caption" => "json",
        "sides_caption" => "json",
        "related_caption" => "json",
        "liked_caption" => "json",
        "desserts_caption" => "json",

    ];
    protected $fillable = [
        'sequence',
        'categ_id',
        'company_id',
        'type',
        'default_code',
        'name',
        'description',
        'list_price',
        'sale_ok',
        'active',
        'app_publish',
        // 'discount',
        'kitchen_id',
        'preparing_time',
        'is_add_ons',
        'is_ingredient',
        'default_drink_id',
        'default_sides_id',
        'drinks_caption',
        'sides_caption',
        'related_caption',
        'liked_caption',
        'desserts_caption',
        'drinks_mendatory',
        'sides_mendatory',
        'is_combo',
        'categ_id',
        'company_id',
        'default_drink_id',
        'default_sides_id',
        'kitchen_id',
        'image',
        'tax_included',

    ];
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'categ_id');
    }
    public function tags()
    {
        return $this->belongsToMany(ProductTag::class, 'product_tag_product_template_rels', 'product_template_id', 'product_tag_id');
    }
    // public function attributes()
    // {
    //     return $this->belongsToMany(ProductAttribute::class, 'product_attribute_product_template_rels', 'product_template_id', 'product_attribute_id');
    // }
    public function products()
    {
        return $this->hasMany(ProductProduct::class, 'product_tmpl_id');
    }
    // public function productDrinks()
    // {
    //     return $this->belongsTo(ProductProduct::class, 'default_drink_id');
    // }
    // public function productSides()
    // {
    //     return $this->belongsTo(ProductProduct::class, 'default_sides_id');
    // }

    public function pricelistItems()
    {
        return $this->hasMany(ProductPricelistItem::class, 'product_tmpl_id');
    }


    public function kitchen()
    {
        return $this->belongsTo(DigitileKitchen::class, 'kitchen_id');
    }
    public function company()
    {
        return $this->belongsTo(ResCompany::class, 'company_id');
    }
    public function productWishlists()
    {
        return $this->hasMany(ProductWishlist::class, 'product_template_id');
    }

    // public function relatedProducts()
    // {
    //     return $this->belongsToMany(ProductProduct::class, 'cat_product_related_rels', 'product_template_id', 'product_product_id');
    // }
    public function productAddons()
    {
        return $this->belongsToMany(ProductProduct::class, 'product_addons_rels', 'product_id', 'addons_id');
    }
    public function productRemovables()
    {
        return $this->belongsToMany(ProductProduct::class, 'product_removable_ingredient_rels', 'product_id', 'removable_ingredient_id');
    }
    public function productIngredients()
    {
        return $this->belongsToMany(ProductProduct::class, 'product_ingredient_rels', 'product_id', 'ingredient_id');
    }
    public function productRelatedDrinks()
    {
        return $this->belongsToMany(ProductProduct::class, 'drinks_product_related_rels', 'product_template_id', 'product_product_id');
    }

    public function relatedLikedProducts()
    {
        return $this->belongsToMany(ProductProduct::class, 'liked_product_related_rels', 'product_template_id', 'product_product_id');
    }
    public function productContent()
    {
        return $this->belongsToMany(ProductProduct::class, 'product_content_rels', 'product_id', 'product_content_id');
    }
    public function relatedProducts()
    {
        return $this->belongsToMany(ProductProduct::class, 'related_product_rels', 'template_id', 'related_id');
    }
    public function sideProducts()
    {
        return $this->belongsToMany(ProductProduct::class, 'sides_product_rels', 'template_id', 'side_id');
    }
    public function dessertProducts()
    {
        return $this->belongsToMany(ProductProduct::class, 'dessert_product_rels', 'template_id', 'dessert_id');
    }

    //RELATIONS 
    public function attributes()
    {
        return $this->hasMany(ProductTemplateAttributeLine::class, 'product_tmpl_id');
    }
    // public function attributes()
    // {
    //     return $this->belongsToMany(ProductAttribute::class, 'product_template_attribute_lines', 'product_tmpl_id', 'attribute_id');
    // }
    // public function attributeValues()
    // {
    //     return $this->hasMany(ProductTemplateAttributeValue::class, 'product_tmpl_id');
    // }
    public function attributeValues()
    {
        return $this->belongsToMany(ProductAttributeValue::class, 'product_template_attribute_values', 'product_tmpl_id', 'p_a_value_id');
    }
    public function attributeValuesPivot()
    {
        return $this->hasMany(ProductTemplateAttributeValue::class, 'product_tmpl_id');
    }

    public function mainPageSections()
    {
        return $this->belongsToMany(MainPageSection::class, 'product_main_page_section_rels', 'product_id', 'main_page_section_id');
    }
}
