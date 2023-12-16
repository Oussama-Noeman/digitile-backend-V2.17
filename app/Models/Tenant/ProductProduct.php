<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductProduct extends Model
{
  use HasFactory;

  protected $casts = [
    'drinks_caption' => 'json',
    'description' => 'json',
    'sides_caption' => 'json',
    'related_caption' => 'json',
    'liked_caption' => 'json',
    'desserts_caption' => 'json',
    'name' => 'json',
  ];

  protected $table = 'product_products';
  protected $fillable = [
    'image',
    'product_default_sides_id',
    'product_default_drink_id',
    'product_tmpl_id',
    'sequence',
    'default_code',
    'barcode',
    'combination_indices',
    'active',
    'default',
    'categ_id',
    'company_id',
    'type',
    'name',
    'description',
    'lst_price',
    'sale_ok',
    'app_publish',
    'discount',
    'kitchen_id',
    'preparing_time',
    'name_ar',
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
    'related_att_values',
    'template_name',
    'variant_name',
    'p_t_a_v_line',
    'tax_included',
  ];
  public function category()
  {
    return $this->belongsTo(ProductCategory::class, 'categ_id');
  }
  public function productTemplate()
  {
    return $this->belongsTo(ProductTemplate::class, 'product_tmpl_id');
  }
  public function productTemplateDrinks()
  {
    return $this->hasMany(ProductTemplate::class, 'default_drink_id');
  }

  public function productTemplatSides()
  {
    return $this->hasMany(ProductTemplate::class, 'default_sides_id');
  }
  public function templatesOfrelatedProducts()
  {
    return $this->belongsToMany(ProductTemplate::class, 'cat_product_related_rels', 'product_product_id', 'product_template_id');
  }
  public function templateOfAddons()
  {
    return $this->belongsToMany(ProductTemplate::class, 'product_addons_rels', 'addons_id', 'product_id');
  }
  public function templateOfRemovables()
  {
    return $this->belongsToMany(ProductTemplate::class, 'product_removable_ingredient_rels', 'removable_ingredient_id', 'product_id');
  }
  public function templateOfIngredients()
  {
    return $this->belongsToMany(ProductTemplate::class, 'product_ingredient_rels', 'ingredient_id', 'product_id');
  }
  public function templateOfRelatedDrinks()
  {
    return $this->belongsToMany(ProductTemplate::class, 'drinks_product_related_rels', 'product_product_id', 'product_template_id');
  }
  public function templateOfRelatedlikedProducts()
  {
    return $this->belongsToMany(ProductTemplate::class, 'liked_product_related_rels', 'product_product_id', 'product_template_id');
  }
  public function saleOrderLines()
  {
    return $this->hasMany(SaleOrderLine::class, 'product_id');
  }
  public function productWishlists()
  {
    return $this->hasMany(ProductWishlist::class, 'product_id');
  }
  public function productPriceListItems()
  {
    return $this->hasMany(ProductPricelistItem::class, 'product_id');
  }

  public function tags()
  {
    return $this->belongsToMany(ProductTag::class, 'product_tag_product_product_rels', 'product_product_id', 'product_tag_id');
  }
  public function pt_attributesValues()
  {
    return $this->belongsToMany(ProductTemplateAttributeValue::class, 'product_variant_combinations', 'p_p_id', 'p_t_a_value_id');
  }
  public function productCategories()
  {
    return $this->belongsToMany(ProductCategory::class, 'product_category_product_product_rels', 'product_product_id', 'product_category_id');
  }


  public function relatedLikedProducts()
  {
    return $this->belongsToMany(ProductProduct::class, 'liked_product_products', 'product_id', 'liked_id');
  }
  public function productRelatedDrinks()
  {
    return $this->belongsToMany(ProductProduct::class, 'product_product_drinks', 'product_id', 'drink_id');
  }
  public function productIngredients()
  {
    return $this->belongsToMany(ProductProduct::class, 'product_product_ingredients', 'product_id', 'ingredient_id');
  }
  public function productRemovables()
  {
    return $this->belongsToMany(ProductProduct::class, 'product_product_removables', 'product_id', 'removable_id');
  }
  public function productAddons()
  {
    return $this->belongsToMany(ProductProduct::class, 'product_product_addons', 'product_id', 'addons_id');
  }
  public function relatedProducts()
  {
    return $this->belongsToMany(ProductProduct::class, 'related_product_products', 'product_id', 'related_id');
  }
  public function company()
  {
    return $this->belongsTo(ResCompany::class, 'company_id');
  }
  public function kitchen()
  {
    return $this->belongsTo(DigitileKitchen::class, 'kitchen_id');
  }
  public function productSide()
  {
    return $this->belongsTo(ProductProduct::class, 'product_default_sides_id');
  }
  public function productSides()
  {
    return $this->hasMany(ProductProduct::class, 'product_default_sides_id');
  }
  public function productDrink()
  {
    return $this->belongsTo(ProductProduct::class, 'product_default_drink_id');
  }
  public function productDrinks()
  {
    return $this->hasMany(ProductProduct::class, 'product_default_drink_id');
  }

  // public function mainPageSections()
  // {
  //   return $this->belongsToMany(MainPageSection::class, 'product_main_page_section_rels', 'product_id', 'main_page_section_id');
  // }


  public function productCategoryDrinks()
  {
    return $this->belongsToMany(ProductCategory::class, 'product_category_drinks', 'drink_id', 'category_id');
  }
  public function productCategorySides()
  {
    return $this->belongsToMany(ProductCategory::class, 'product_category_sides', 'sides_id', 'category_id');
  }
  public function productCategoryRelated()
  {
    return $this->belongsToMany(ProductCategory::class, 'product_category_related_products', 'related_id', 'category_id');
  }
  public function productCategoryLiked()
  {
    return $this->belongsToMany(ProductCategory::class, 'product_category_liked_products', 'liked_id', 'category_id');
  }
  public function productCategoryDesserts()
  {
    return $this->belongsToMany(ProductCategory::class, 'product_category_desserts_products', 'dessert_id', 'category_id');
  }
  public function relatedTemplates()
  {
    return $this->belongsToMany(ProductTemplate::class, 'related_product_rels', 'related_id', 'template_id');
  }
  public function sideTemplates()
  {
    return $this->belongsToMany(ProductTemplate::class, 'sides_product_rels', 'side_id', 'template_id');
  }
  public function dessertTemplates()
  {
    return $this->belongsToMany(ProductProduct::class, 'dessert_product_rels', 'dessert_id', 'template_id');
  }
}
