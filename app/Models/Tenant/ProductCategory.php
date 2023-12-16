<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
  use HasFactory;
  protected $table = 'product_categories';
  protected $fillable = [
    'parent_id',
    'company_id',
    'name',
    'complete_name',
    'parent_path',
    'removal_strategy_id',
    'packaging_reserve_method',
    'name_ar',
    'is_grocery',
    'is_main',
    'is_publish',
    'image',
  ];
  protected $casts = ['name' => 'json'];
  public function productProducts()
  {
    return $this->hasMany(ProductProduct::class, 'categ_id');
  }
  public function company()
  {
    return $this->belongsTo(ResCompany::class, 'company_id');
  }
  public function productTemplates()
  {
    return $this->hasMany(ProductTemplate::class, 'categ_id');
  }
  public function productProduct()
  {
    return $this->belongsToMany(ProductProduct::class, 'product_category_product_product_rels', 'product_category_id', 'product_product_id');
  }
  public function priceListItems()
  {
    return $this->hasMany(ProductPricelistItem::class, 'categ_id');
  }
  public function parent()
  {
    return $this->belongsTo(ProductCategory::class, 'parent_id', 'id');
  }

  public function children()
  {
    return $this->hasMany(ProductCategory::class, 'id', 'parent_id');
  }


  public function productCategoryDrinks()
  {
    return $this->belongsToMany(ProductProduct::class, 'product_category_drinks', 'category_id', 'drink_id');
  }
  public function productCategorySides()
  {
    return $this->belongsToMany(ProductProduct::class, 'product_category_sides', 'category_id', 'sides_id');
  }
  public function productCategoryRelated()
  {
    return $this->belongsToMany(ProductProduct::class, 'product_category_related_products', 'category_id', 'related_id');
  }
  public function productCategoryLiked()
  {
    return $this->belongsToMany(ProductProduct::class, 'product_category_liked_products', 'category_id', 'liked_id');
  }
  public function productCategoryDesserts()
  {
    return $this->belongsToMany(ProductProduct::class, 'product_category_desserts_products', 'category_id', 'dessert_id');
  }
}
