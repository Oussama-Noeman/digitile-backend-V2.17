<?php

namespace App\Utils;

use App\Models\DriverChat;
use App\Models\LatitudeLongitude;
use App\Models\MainBanner1;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Models\ProductCategory;
use App\Models\ProductPricelist;
use App\Models\ProductProduct;
use App\Models\ProductTemplate;
use App\Models\ProductTemplateAttributeLine;
use App\Models\ProductTemplateAttributeValue;
use App\Models\ProductVariantCombination;
use App\Models\ResCompany;
use App\Models\ResPartner;
use App\Models\SaleOrder;
use App\Models\ZoneZone;
use Exception;
use Illuminate\Http\Request;

class CustomHelper
{
    public static function formatTimeFromFloat($floatTime)
    {
        $hours = (int)$floatTime;
        $minutes = round(($floatTime - $hours) * 60);
        $formattedTime = sprintf("%02d:%02d", $hours, $minutes);
        return $formattedTime;
    }

    public static function changeParagToLine($parag)
    {
        if ($parag) {
            $desc = strip_tags($parag);
            $desc = trim($desc);
        } else {
            $desc = '';
        }
        return $desc;
    }
    public function getSettings(Request $request)
    {
        try {
            $requestData = json_decode($request->getContent(), true);
            $company_id = $requestData['company_id'] ?? null;

            if ($company_id) {
                $restaurantName = ResCompany::find($company_id);
            } else {
                $restaurantName = ResCompany::where('parent_id', null)->orderBy('id')->first();
            }
        } catch (Exception $e) {
            $restaurantName = ResCompany::where('parent_id', null)->orderBy('id')->first();
        }

        if ($restaurantName) {
            $calendar = $restaurantName->calendar;

            $restaurantScheduleTime = [];

            if ($calendar) {
                $calendarAttendance = $calendar->attendance()
                    ->orderBy('dayofweek')
                    ->orderBy('hour_from')
                    ->get();

                foreach ($calendarAttendance as $att) {
                    $valuesAtt = [
                        "day_name" => trans("days." . $att->dayofweek),
                        "day" => (int)$att->dayofweek <= 6 ? (int)$att->dayofweek : 0,
                        "opening_time" => $this->formatTimeFromFloat($att->hour_from),
                        "closing_time" => $this->formatTimeFromFloat($att->hour_to)
                    ];

                    $restaurantScheduleTime[] = $valuesAtt;
                }
            }

            // Rewrite the remaining code for banners and other data retrieval

            $values = [
                "company_id" => $restaurantName->id,
                "restaurant_name" => $restaurantName->name,
                "restaurant_address" => $restaurantName->street,
                "restaurant_phone" => $restaurantName->phone,
                "restaurant_email" => $restaurantName->email,
                "currency_symbol" => $restaurantName->currency->name,
                // ... continue with other values
            ];

            return response()->json(['status' => 200, 'response' => $values, 'message' => 'Config Found']);
        } else {
            return response()->json(['status' => 404, 'message' => 'No data Found!']);
        }
    }
    public function getAddOns(ProductTemplate $productTemplate, $lang)
    {
        $productAddOnsList = [];

        $addOns = $productTemplate->productAddons;

        foreach ($addOns as $addOn) {
            $valuesAddOns = $this->getProductFullProductDetails($addOn, $lang);
            $productAddOnsList[] = $valuesAddOns;
        }

        return $productAddOnsList;
    }

    public function getIngredients(ProductTemplate $productTemplate, $lang)
    {
        $productIngredientList = [];

        $ingredients = $productTemplate->productIngredients;

        foreach ($ingredients as $ingredient) {
            $valuesIngredient = $this->getProductFullProductDetails($ingredient, $lang);
            $productIngredientList[] = $valuesIngredient;
        }

        return $productIngredientList;
    }

    public function getRemovableIngredients(ProductTemplate $productTemplate, $lang)
    {
        $productRemovableIngredientList = [];

        $removableIngredients = $productTemplate->productRemovables;

        foreach ($removableIngredients as $removableIngredient) {
            $valuesRemovableIngredient = $this->getProductFullProductDetails($removableIngredient, $lang);
            $productRemovableIngredientList[] = $valuesRemovableIngredient;
        }

        return $productRemovableIngredientList;
    }
    public function getProductProductSdDetails($product_product, $name_add = null, $Price_add = null, $default = null, $lang )
    {
        $variant_name = $this->getProductVariantName($product_product);
        if ($variant_name !== '') {
            $variant_name = ' (' . $variant_name . ')';
        }

        if ($name_add) {
            $variant_name = $variant_name . '(' . $name_add . ')';
        }

        if ($Price_add) {
            $price = $product_product->lst_price + $Price_add;
        } else {
            $price = $product_product->lst_price;
        }

        $price_product = Tax::computeProductProductTax($product_product, $price);


        $values_prod = [
            "product_id" => $product_product->id,
            "product_templ_id" => $product_product->product_tmpl_id,
            "product_name" =>  Lang::get_name($product_product->name, $lang) . $variant_name,
            "price" => $this->getProductProductPrice($product_product, $price_product),
            "price_without_TVA" => $this->getProductProductPrice($product_product, $price),
            "product_image" => '/storage/' . $product_product->image,
            "default" => $default,
        ];

        return $values_prod;
    }

    public function getProductFullProductSdDetails($product_product, $default, $lang )
    {
        $product_variant_list = [];

        $values_prod = $this->getProductProductSdDetails($product_product, '', 0, $default, $lang );
        $product_variant_list[] = $values_prod;

        return $product_variant_list;
    }

    public function getRelatedProduct(ProductTemplate $productTemplate, $lang)
    {
        $products = [];
        if ($productTemplate) {
            foreach ($productTemplate->relatedProducts as $relt) {
                $default = false;
                $related = $this->getProductFullProductSdDetails($relt, $default, $lang);
                $products[] = $related;
            }
        }

        return $products;
    }

    public function getSidesProduct(ProductTemplate $productTemplate, $lang)
    {
        $products = [];
        if ($productTemplate) {
            foreach ($productTemplate->sideProducts as $relt) {
                $side = $this->getProductFullProductSdDetails($relt, false, $lang);
                $products[] = $side;
            }
        }

        return $products;
    }

    public function getDrinksProduct(ProductTemplate $productTemplate, $lang)
    {
        $products = [];
        if ($productTemplate) {
            foreach ($productTemplate->productRelatedDrinks as $relt) {
                $drinks = $this->getProductFullProductSdDetails($relt, false, $lang);
                $products[] = $drinks;
            }
        }

        return $products;
    }

    public function getDessertsProduct(ProductTemplate $productTemplate, $lang)
    {
        $products = [];
        if ($productTemplate) {
            foreach ($productTemplate->dessertProducts as $relt) {
                $default = false;
                $desserts = $this->getProductFullProductSdDetails($relt, $default, $lang);
                $products[] = $desserts;
            }
        }

        return $products;
    }

    public function getAlsoLikeProduct(ProductTemplate $productTemplate, $lang)
    {
        $products = [];
        if ($productTemplate) {
            foreach ($productTemplate->relatedLikedProducts as $relt) {
                $default = false;
                $liked = $this->getProductFullProductSdDetails($relt, $default, $lang);
                $products[] = $liked;
            }
        }

        return $products;
    }

    public function generateCombinations($inputData)
    {
        $attributeValueLists = array_column($inputData, 'valueList');
        $allCombinations = [];
        //  \CartesianProduct\CartesianProduct::create($attributeValueLists)->toArray();

        $formattedCombinations = [];

        foreach ($allCombinations as $combination) {
            $attributesWithPrices = array_map(function ($attribute, $value) {
                return [
                    'attribute_id' => $attribute['attribute_id'],
                    'attribute_name' => $attribute['attribute_name'],
                    'attribute_value_id' => $value['value_id'],
                    'attribute_value_name' => $value['value_name'],
                ];
            }, $inputData, $combination);

            $names = array_map(function ($value) {
                return $value['value_name'];
            }, $combination);

            $totalPrice = array_sum(array_column($combination, 'value_price'));

            $formattedCombination = [
                'name' => implode(', ', $names),
                'price' => $totalPrice,
            ];

            $formattedCombinations[] = $formattedCombination;
        }

        return $formattedCombinations;
    }

    public function getProductTemplateDetails(ProductTemplate $productTemplate, $lang)
    {  /// need to check the logic
        $productVariantList = [];
        $productProducts = $productTemplate->products->where('active', true);
        foreach ($productProducts as $productProduct) {
            $valuesProd = $this->getProductProductDetails($productProduct, '', 0, $lang);
            $productVariantList[] = $valuesProd;
        }

        return $productVariantList;
    }

    public function getProductFullProductDetails($productProduct, $lang = 'en',$AddProductInfo=null)
    {
        $productVariantList = [];

        $valuesProd = $this->getProductProductDetails($productProduct, '', 0, $lang, $AddProductInfo);

        $productVariantList[] = $valuesProd;

        return $productVariantList;
    }
    public function getProductProductDetails(ProductProduct $productProduct, $nameAdd = null, $priceAdd = null, $lang = 'en', $AddProductInfo=null)
    {
        $productVariantName = '';
        $variantAttribute = [];

        $extraPrice = 0;

        if ($productProduct->pt_attributesValues) {
            foreach ($productProduct->pt_attributesValues as $attributeValue) {
                $extraPrice += $attributeValue->price_extra;
            }

            foreach ($productProduct->pt_attributesValues as $variant) {
                $valuesVariantAttribute = [
                    'attribute_id' => $variant->attribute_id,
                    'attribute_name' => $variant->attribute->name,
                    'attribute_value_id' => $variant->p_a_value_id,
                    'attribute_value_name' => $variant->value_name,
                ];

                $productVariantName = $productVariantName . ',' . $variant->name;
                $variantAttribute[] = $valuesVariantAttribute;
            }

            if ($productVariantName != '') {
                $productVariantName = substr($productVariantName, 1);
            } else {
                $productVariantName = $productProduct->name;
            }
        } else {
            $productVariantName = $productProduct->name;
        }

        if ($productProduct->detailedType === 'product') {
            $storable = true;
        } else {
            $storable = false;
        }

        $variantName = $this->getProductVariantName($productProduct);

        if ($variantName != '') {
            $variantName = ' (' . $variantName . ')';
        }

        if ($nameAdd) {
            $variantName = $variantName . '(' . $nameAdd . ')';
        }

        if ($priceAdd) {
            $price = $productProduct->lst_price + $priceAdd;
        } else {
            $price = $productProduct->lst_price;
        }

        $priceProduct = Tax::computeProductProductTax($productProduct, $price);
        if ($productProduct->default)
            $default = true;
        else
            $default = false;



            if ($AddProductInfo ){


                $valuesProd = [
                    'product_product_id' => $productProduct->id,
                    "product_templ_id" => $productProduct->product_tmpl_id,
                    'product_variant_name' => Lang::get_name($productProduct->name, $lang) . $variantName,
                    'default' => $default,
                    'storable' => $storable,
                    'product_sale_price' => $priceProduct,
                    'final_price' => $this->getProductProductPrice($productProduct, $priceProduct),
                    'final_price_Without_TVA' => $this->getProductProductPrice($productProduct, $price),
                    'product_image' => '/storage/' . $productProduct->image,
                    'variant_attribute_list' => $variantAttribute,
                    'product_product_info'=> $this->getProductProductInformationById($productProduct,   $lang),
                ];
            }
            else{

                $valuesProd = [
                    'product_product_id' => $productProduct->id,
                    "product_templ_id" => $productProduct->product_tmpl_id,
                    'product_variant_name' => Lang::get_name($productProduct->name, $lang) . $variantName,
                    'default' => $default,
                    'storable' => $storable,
                    'product_sale_price' => $priceProduct,
                    'final_price' => $this->getProductProductPrice($productProduct, $priceProduct),
                    'final_price_Without_TVA' => $this->getProductProductPrice($productProduct, $price),
                    'product_image' => '/storage/' . $productProduct->image,
                    'variant_attribute_list' => $variantAttribute,
                ];
            }

        // $valuesProd = [
        //     'product_product_id' => $productProduct->id,
        //     "product_templ_id" => $productProduct->product_tmpl_id,
        //     'product_variant_name' => Lang::get_name($productProduct->template_name, $lang) . '(' . $variantName . ')',
        //     'default' => $default,
        //     'storable' => $storable,
        //     'product_sale_price' => $priceProduct,
        //     'final_price' => $this->getProductProductPrice($productProduct, $priceProduct),
        //     'final_price_Without_TVA' => $this->getProductProductPrice($productProduct, $price),
        //     'product_image' => '/storage/' . $productProduct->image,
        //     'variant_attribute_list' => $variantAttribute,
        // ];


        return $valuesProd;
    }






    public function getProductVariantName(ProductProduct $productProduct)
    {
        $productVariantName = '';


        if ($productProduct->pt_attributesValues) {
            foreach ($productProduct->pt_attributesValues as $variant) {
                $productVariantName = $productVariantName . ',' . $variant->attribute->name;
            }

            if ($productVariantName != '') {
                $productVariantName = substr($productVariantName, 1);
            }
        }

        return $productVariantName;
    }


    // 2
    public function getAttributeProductProduct(ProductProduct $productProduct, $lang)
    {
        $attributeList = [];

        if ($productProduct->p_t_a_v_line) {
            $productTemplateAttributeValueIds = explode(',', $productProduct->p_t_a_v_line);

            $productTemplateAttributeValues = ProductTemplateAttributeValue::whereIn('id', $productTemplateAttributeValueIds)->get();

            if ($productTemplateAttributeValues->isNotEmpty()) {
                foreach ($productTemplateAttributeValues as $productTemplateAttributeValue) {
                    $price = 0;
                    $valueList = [];
                    $attValue = ProductAttributeValue::where('id', $productTemplateAttributeValue->product_attribute_value_id)->first();
                    $att = ProductAttribute::where('id', $productTemplateAttributeValue->attribute_id)->first();

                    if ($productTemplateAttributeValue->ptav_active) {
                        $price += $productTemplateAttributeValue->price_extra;
                    }

                    $default = true;

                    $valAtt = [
                        "value_id" => $attValue->id,
                        "value_name" => $attValue->name,
                        "value_price" => $price,
                        "default" => $default,
                    ];

                    $valueList[] = $valAtt;

                    $valuesProd = [
                        "attribute_id" => $att->id,
                        "attribute_name" => $att->name,
                        "value_list" => $valueList,
                    ];

                    $attributeList[] = $valuesProd;
                }
            }
        }

        return $attributeList;
    }

    public function getAttributeProductProductAsMannasat(ProductProduct $productProduct, $lang)
    {
        $attributeList = [];

        if ($productProduct->p_t_a_v_line) {
            $productTemplateAttributeValueIds = explode(',', $productProduct->p_t_a_v_line);
            $productTemplateAttributeValues = ProductTemplateAttributeValue::whereIn('id', $productTemplateAttributeValueIds)->get();
            if ($productTemplateAttributeValues->isNotEmpty()) {
                foreach ($productTemplateAttributeValues as $productTemplateAttributeValue) {
                    $price = 0;
                    $valueList = [];
                    $attValue = ProductAttributeValue::where('id', $productTemplateAttributeValue->p_a_value_id)->first();
                    $att = ProductAttribute::where('id', $productTemplateAttributeValue->attribute_id)->first();
                    if ($productTemplateAttributeValue->ptav_active) {
                        $price += $productTemplateAttributeValue->price_extra;
                    }

                    $default = true;

                    $valAtt = [
                        "value_id" => $attValue->id,
                        "label" => $attValue->name,
                        "optionPrice" => $price,
                    ];

                    $valueList[] = $valAtt;

                    $valuesProd = [
                        "id" => $att->id,
                        "name" => $att->name,
                        "type" => "multi",
                        "min" => "1",
                        "max" => "3",
                        "required" => "off",
                        "values" => $valueList,
                    ];

                    $attributeList[] = $valuesProd;
                }
            }
        }

        return $attributeList;
    }

    public function getAttributeProduct(ProductTemplate $productTemplate, $lang)
    {
        $productDefault = ProductTemplate::where('product_tmpl_id', $productTemplate->id)->where('default', true)->first();
        $productAttribute = ProductTemplateAttributeLine::where('product_tmpl_id', $productTemplate->id);

        if ($lang == "ar") {
            $productAttribute->with(['productAttribute' => function ($query) {
                $query->withContext('ar_001');
            }]);
        }

        $productAttribute = $productAttribute->get();
        $attributeList = [];

        if ($productAttribute->isNotEmpty()) {
            foreach ($productAttribute as $att) {
                $valueList = [];
                $attValue = ProductAttributeValue::where('attribute_id', $att->attribute_id)->get();

                if ($lang == "ar") {
                    $attValue->each(function ($item) {
                        $item->withContext('ar_001');
                    });
                }

                foreach ($attValue as $val) {
                    $productTemplateAttributeValue = ProductTemplateAttributeValue::where('attribute_id', $att->attribute_id)
                        ->where('product_attribute_value_id', $val->id)
                        ->where('product_tmpl_id', $productTemplate->id)
                        ->first();

                    if ($productTemplateAttributeValue) {
                        if ($productTemplateAttributeValue->ptav_active) {
                            $price = $productTemplateAttributeValue->price_extra;
                        } else {
                            $price = 0;
                        }
                    } else {
                        $price = 0;
                    }

                    $default = false;

                    if ($productDefault) {
                        foreach ($productDefault->productTemplateVariantValueIds as $variant) {
                            if ($att->attribute_id == $variant->attribute_id) {
                                $attributeValue = ProductTemplateAttributeValue::where('id', $variant->id)->first();
                                if ($val->id == $attributeValue->product_attribute_value_id) {
                                    $default = true;
                                }
                            }
                        }
                    }

                    $valAtt = [
                        "value_id" => $val->id,
                        "value_name" => $val->name,
                        "value_price" => $price,
                        "default" => $default,
                    ];

                    $valueList[] = $valAtt;
                }

                $valuesProd = [
                    "attribute_id" => $att->attribute_id,
                    "attribute_name" => $att->attribute_name,
                    "value_list" => $valueList,
                ];

                $attributeList[] = $valuesProd;
            }
        }

        return $attributeList;
    }

    public function getProductContents(ProductTemplate $productTemplate, $lang)
    {
        $products = [];
        $productTemplate->productContent;
        if ($productTemplate) {
            foreach ($productTemplate->productContent as $relt) {
                $combo = $this->getProductFullProductDetails($relt, $lang, true);
                $products[] = $combo;
            }
        }




        return $products;
    }


    public function getProductProductInformationById(ProductProduct $product, $lang)
    {
        $values = [];

        if ($product) {
            $details = $this;

            $values = [
                "product_id" => $product->id,
                "product_templ_id" => $product->product_tmpl_id,
                "product_name" => $product->name['en'],
                "product_description" => $product->description_sale,
                "product_main_image" => '/storage/' . $product->image,
                'template_sale_price' => $this->getProductTemplatePrice($product->productTemplate),
                'product_product_details' => $details->getProductFullProductDetails($product, $lang),
                'Addons_details' => $details->getAddOns($product->productTemplate, $lang),
                'Ingredients_details' => $details->getIngredients($product->productTemplate, $lang),
                'Removable_Ingredients_details' => $details->getRemovableIngredients($product->productTemplate, $lang),
            ];
        }

        return $values;
    }
    public function getAttributeProductNew(ProductTemplate $productTemplate, $lang)
    {
        $productDefault = $productTemplate;
        $amountTvaPercent = 0;

        if ($productTemplate->company) {
            $amountTvaPercent = $productTemplate->company->tax;
        } else {
            $amountTvaPercent = ResCompany::first()->tax;
        }

        $attributeList = [];

        if ($productDefault->attributes) {
            foreach ($productDefault->attributes as $att) {
                $valueList = [];
                $attValue = $att->valuesOfAttributes;

                if ($attValue) {
                    foreach ($attValue as $val) {
                        if ($val->ptav_active) {
                            $price = $val->price_extra;
                        } else {
                            $price = 0;
                        }

                        $default = false;
                        if ($productDefault->attributeValues !== null) {
                            foreach ($productDefault->attributeValues as $variant) {
                                if ($val->id == $variant->id) {
                                    $default = true;
                                }
                            }
                        }

                        $priceTva = round($price * (1 + $amountTvaPercent / 100), 2);

                        $value_name = ProductAttributeValue::where('id', $val->id)->first();
                        $valAtt = [
                            "value_id" => $val->id,
                            "value_name" => Lang::get_name($value_name->name, $lang),
                            "value_price" => $priceTva,
                            "value_price_without_TVA" => round($price, 2),
                            "default" => $default,
                        ];

                        $valueList[] = $valAtt;
                    }
                }

                $attribute_name = ProductAttribute::find($att->attribute_id);
                $valuesProd = [
                    "attribute_id" => $att->id,
                    "attribute_name" => Lang::get_name($attribute_name->name, $lang),
                    "value_list" => $valueList,
                ];

                $attributeList[] = $valuesProd;
            }
        }

        return $attributeList;
    }



    public function getProductProductVariantName(ProductProduct $productProduct)
    {
        $productVariantName = '';
        $variantAttribute = [];

        if ($productProduct->productTemplateVariantValueIds->isNotEmpty()) {
            foreach ($productProduct->productTemplateVariantValueIds as $variant) {
                $productVariantName .= ',' . $variant->attribute_id->name . ':' . $variant->name;
            }

            if ($productVariantName != '') {
                $productVariantName = substr($productVariantName, 1);
            } else {
                $productVariantName = $productProduct->name;
            }
        } else {
            $productVariantName = $productProduct->name;
        }

        return $productVariantName;
    }

    public function getProductProductPrice(ProductProduct $productProduct, $priceNew = null)
    {   // need data base modification for lst_price
//        $price = $productProduct->lst_price;
        if ($priceNew){
            $price = $priceNew;
        }
        else {
            $price = $productProduct->lst_price;
        }
        return $price;
        // $price = $this->getProductProductBannerPrice($productProduct, $priceNew);

        // print($price);
        // if ($price) {
        //     return round($price, 2);
        // } else {
        //     $price = $this->getProductProductPromotionPrice($productProduct, $priceNew);

        //     if ($price) {
        //         return round($price, 2);
        //     } else {
        //         $price = $this->getProductProductOfferPrice($productProduct, $priceNew);

        //         if ($price) {
        //             return round($price, 2);
        //         } else {
        //             if ($priceNew) {
        //                 return round($priceNew, 2);
        //             } else {
        //                 return round($productProduct->lst_price, 2);
        //             }
        //         }
        //     }
        // }
    }

    public function getProductProductOfferPrice(ProductProduct $productProduct, $priceNew = null)
    {
        try {
            $product = ProductTemplate::where('id', $productProduct->product_tmpl_id->id)->first();
            $offerBanners = ProductPricelist::where('is_published', true)
                ->where('is_offer', true)
                ->get();

            if ($priceNew) {
                $shelfPrice = $priceNew;
            } else {
                $shelfPrice = $productProduct->lst_price;
            }

            $offerPrice = false;

            if ($offerBanners->isNotEmpty()) {
                foreach ($offerBanners as $banner) {
                    foreach ($banner->item_ids as $line) {
                        if ($line->applied_on == '1_product' && $line->product_tmpl_id->id == $product->id) {
                            $offerPrice = $shelfPrice - (($line->percent_price / 100) * $shelfPrice);
                            return $offerPrice;
                        } elseif ($line->applied_on == '2_product_category') {
                            $category = ProductCategory::where('id', $line->categ_id->id)->first();
                            $productsIds = ProductTemplate::where('categ_id', $category->id)->get();

                            if ($productsIds->contains('id', $product->id)) {
                                $offerPrice = $shelfPrice - (($line->percent_price / 100) * $shelfPrice);
                                return $offerPrice;
                            }
                        } elseif ($line->applied_on == '3_global') {
                            $offerPrice = $shelfPrice - (($line->percent_price / 100) * $shelfPrice);
                            return $offerPrice;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public function getProductProductBannerPrice(ProductProduct $productProduct, $priceNew = null)
    {
        try {
            $product = $productProduct;

            $publishedBanners = ProductPricelist::where('is_published', true)
                ->where('is_banner', true)
                ->get();

            if ($priceNew) {
                $shelfPrice = $priceNew;
            } else {
                //list_price should be lst_price need database modification
                $shelfPrice = $productProduct->lst_price;
            }

            $bannerPrice = false;

            if ($publishedBanners->isNotEmpty()) {
                foreach ($publishedBanners as $banner) {
                    foreach ($banner->productPricelistItems as $line) {
                        if ($line->applied_on == '1_product' && $line->productProduct->id == $product->id) {
                            $bannerPrice = $shelfPrice - (($line->percent_price / 100) * $shelfPrice);
                            return $bannerPrice;
                        } elseif ($line->applied_on == '2_product_category') {
                            $category = ProductCategory::where('id', $line->categ_id->id)->first();
                            $productsIds = ProductTemplate::where('categ_id', $category->id)->get();

                            if ($productsIds->contains('id', $product->id)) {
                                $bannerPrice = $shelfPrice - (($line->percent_price / 100) * $shelfPrice);
                                return $bannerPrice;
                            }
                        } elseif ($line->applied_on == '3_global') {
                            $bannerPrice = $shelfPrice - (($line->percent_price / 100) * $shelfPrice);

                            return $bannerPrice;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public function getProductProductPromotionPrice(ProductProduct $productProduct, $priceNew = null)
    {
        try {
            $product = ProductTemplate::where('id', $productProduct->product_tmpl_id->id)->first();
            $publishedPromotions = ProductPricelist::where('is_published', true)
                ->where('is_promotion', true)
                ->get();

            if ($priceNew) {
                $shelfPrice = $priceNew;
            } else {
                $shelfPrice = $productProduct->lst_price;
            }

            $promotionPrice = false;

            if ($publishedPromotions->isNotEmpty()) {
                foreach ($publishedPromotions as $promotion) {
                    foreach ($promotion->item_ids as $line) {
                        if ($line->applied_on == '1_product' && $line->product_tmpl_id->id == $product->id) {
                            $promotionPrice = $shelfPrice - (($line->percent_price / 100) * $shelfPrice);
                            return $promotionPrice;
                        } elseif ($line->applied_on == '2_product_category') {
                            $category = ProductCategory::where('id', $line->categ_id->id)->first();
                            $productsIds = ProductTemplate::where('categ_id', $category->id)->get();

                            if ($productsIds->contains('id', $product->id)) {
                                $promotionPrice = $shelfPrice - (($line->percent_price / 100) * $shelfPrice);
                                return $promotionPrice;
                            }
                        } elseif ($line->applied_on == '3_global') {
                            $promotionPrice = $shelfPrice - (($line->percent_price / 100) * $shelfPrice);
                            return $promotionPrice;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public function getProductTemplatePrice(ProductTemplate $productTemplate)
    {
        $priceProduct = Tax::computeProductTemplateTax($productTemplate, $productTemplate->list_price);
        return $priceProduct;
        // $price = $this->getProductTemplateBannerPrice($productTemplate, $priceProduct);

        // if ($price) {
        //     return round($price, 2);
        // } else {
        //     $price = $this->getProductTemplatePromotionPrice($productTemplate, $priceProduct);

        //     if ($price) {
        //         return round($price, 2);
        //     } else {
        //         $price = $this->getProductTemplateOfferPrice($productTemplate, $priceProduct);

        //         if ($price) {
        //             return round($price, 2);
        //         } else {
        //             return round($priceProduct, 2);
        //         }
        //     }
        // }
    }

    public function getProductTemplatePriceWithoutTVA(ProductTemplate $productTemplate)
    {
        $priceProduct = $productTemplate->list_price;
        $price = $this->getProductTemplateBannerPrice($productTemplate, $priceProduct);

        if ($price) {
            return round($price, 2);
        } else {
            $price = $this->getProductTemplatePromotionPrice($productTemplate, $priceProduct);

            if ($price) {
                return round($price, 2);
            } else {
                $price = $this->getProductTemplateOfferPrice($productTemplate, $priceProduct);

                if ($price) {
                    return round($price, 2);
                } else {
                    return round($priceProduct, 2);
                }
            }
        }
    }

    public function getProductTemplateOfferPrice($product_template, $price_product)
    {
        try {
            $offer_banners = ProductPriceList::where('is_published', true)
                ->where('is_offer', true)
                ->get();

            if ($price_product) {
                $shelf_price = $price_product; // $product_template->list_price
            } else {
                $shelf_price = $product_template->list_price;
            }

            $offer_price = false;

            if ($offer_banners) {
                foreach ($offer_banners as $line) {
                    if ($line->applied_on == '1_product' && $line->product_tmpl_id == $product_template->id) {
                        $offer_price = $shelf_price - ($line->percent_price / 100) * $shelf_price;
                        return round($offer_price, 2);
                    } elseif ($line->applied_on == '2_product_category') {
                        $category_id = ProductCategory::where('id', $line->categ_id)
                            ->first();
                        $products_ids = ProductTemplate::where('categ_id', $category_id->id)
                            ->get();
                        if ($this->isProductTemplateInArray($product_template, $products_ids)) {
                            $offer_price = $shelf_price - ($line->percent_price / 100) * $shelf_price;
                            return round($offer_price, 2);
                        }
                    } elseif ($line->applied_on == '3_global') {
                        $offer_price = $shelf_price - ($line->percent_price / 100) * $shelf_price;
                        return round($offer_price, 2);
                    }
                }
            }
        } catch (Exception $e) {
            return false;
        }
    }

    private function isProductTemplateInArray($product_template, $product_templates)
    {
        foreach ($product_templates as $product) {
            if ($product->id == $product_template->id) {
                return true;
            }
        }
        return false;
    }

    public function getOfferPrice(ProductTemplate $productTemplate, $priceProduct)
    {
        $offerBanners = ProductPricelist::where('is_published', true)
            ->where('is_offer', true)
            ->get();

        if ($priceProduct) {
            $shelfPrice = $priceProduct;
        } else {
            $shelfPrice = $productTemplate->list_price;
        }

        $offerPrice = false;

        if ($offerBanners->isNotEmpty()) {
            foreach ($offerBanners as $banner) {
                foreach ($banner->item_ids as $line) {
                    if ($line->applied_on == '1_product' && $line->product_tmpl_id->id == $productTemplate->id) {
                        $offerPrice = $shelfPrice - (($line->percent_price / 100) * $shelfPrice);
                        return $offerPrice;
                    } elseif ($line->applied_on == '2_product_category') {
                        $category = ProductCategory::where('id', $line->categ_id->id)->first();
                        $productsIds = ProductTemplate::where('categ_id', $category->id)->get();

                        if ($productsIds->contains('id', $productTemplate->id)) {
                            $offerPrice = $shelfPrice - (($line->percent_price / 100) * $shelfPrice);
                            return $offerPrice;
                        }
                    } elseif ($line->applied_on == '3_global') {
                        $offerPrice = $shelfPrice - (($line->percent_price / 100) * $shelfPrice);
                        return $offerPrice;
                    }
                }
            }
        }

        return false;
    }

    private function getOfferPriceWithPriceNew($productTemplate, $priceNew)
    {
        $offerBanners = ProductPricelist::where('is_published', true)
            ->where('is_offer', true)
            ->get();

        if ($priceNew) {
            $shelfPrice = $priceNew;
        } else {
            $shelfPrice = $productTemplate->list_price;
        }

        $offerPrice = false;

        if ($offerBanners->isNotEmpty()) {
            foreach ($offerBanners as $banner) {
                foreach ($banner->item_ids as $line) {
                    if ($line->applied_on == '1_product' && $line->product_tmpl_id->id == $productTemplate->id) {
                        $offerPrice = $shelfPrice - (($line->percent_price / 100) * $shelfPrice);
                        return $offerPrice;
                    } elseif ($line->applied_on == '2_product_category') {
                        $category = ProductCategory::where('id', $line->categ_id->id)->first();
                        $productsIds = ProductTemplate::where('categ_id', $category->id)->get();

                        if ($productsIds->contains('id', $productTemplate->id)) {
                            $offerPrice = $shelfPrice - (($line->percent_price / 100) * $shelfPrice);
                            return $offerPrice;
                        }
                    } elseif ($line->applied_on == '3_global') {
                        $offerPrice = $shelfPrice - (($line->percent_price / 100) * $shelfPrice);
                        return $offerPrice;
                    }
                }
            }
        }

        return false;
    }

    private function getBannerPriceWithPriceNew($productTemplate, $priceNew)
    {
        $publishedBanners = ProductPricelist::where('is_published', true)
            ->where('is_banner', true)
            ->get();

        if ($priceNew) {
            $shelfPrice = $priceNew;
        } else {
            $shelfPrice = $productTemplate->list_price;
        }

        $bannerPrice = false;

        if ($publishedBanners->isNotEmpty()) {
            foreach ($publishedBanners as $banner) {
                foreach ($banner->item_ids as $line) {
                    if ($line->applied_on == '1_product' && $line->product_tmpl_id->id == $productTemplate->id) {
                        $bannerPrice = $shelfPrice - (($line->percent_price / 100) * $shelfPrice);
                        return $bannerPrice;
                    } elseif ($line->applied_on == '2_product_category') {
                        $category = ProductCategory::where('id', $line->categ_id->id)->first();
                        $productsIds = ProductTemplate::where('categ_id', $category->id)->get();

                        if ($productsIds->contains('id', $productTemplate->id)) {
                            $bannerPrice = $shelfPrice - (($line->percent_price / 100) * $shelfPrice);
                            return $bannerPrice;
                        }
                    } elseif ($line->applied_on == '3_global') {
                        $bannerPrice = $shelfPrice - (($line->percent_price / 100) * $shelfPrice);
                        return $bannerPrice;
                    }
                }
            }
        }

        return false;
    }

    private function getPromotionPriceWithPriceNew($productTemplate, $priceNew)
    {
        $publishedPromotions = ProductPricelist::where('is_published', true)
            ->where('is_promotion', true)
            ->get();

        if ($priceNew) {
            $shelfPrice = $priceNew;
        } else {
            $shelfPrice = $productTemplate->list_price;
        }

        $promotionPrice = false;

        if ($publishedPromotions->isNotEmpty()) {
            foreach ($publishedPromotions as $promotion) {
                foreach ($promotion->item_ids as $line) {
                    if ($line->applied_on == '1_product' && $line->product_tmpl_id->id == $productTemplate->id) {
                        $promotionPrice = $shelfPrice - (($line->percent_price / 100) * $shelfPrice);
                        return $promotionPrice;
                    } elseif ($line->applied_on == '2_product_category') {
                        $category = ProductCategory::where('id', $line->categ_id->id)->first();
                        $productsIds = ProductTemplate::where('categ_id', $category->id)->get();

                        if ($productsIds->contains('id', $productTemplate->id)) {
                            $promotionPrice = $shelfPrice - (($line->percent_price / 100) * $shelfPrice);
                            return $promotionPrice;
                        }
                    } elseif ($line->applied_on == '3_global') {
                        $promotionPrice = $shelfPrice - (($line->percent_price / 100) * $shelfPrice);
                        return $promotionPrice;
                    }
                }
            }
        }

        return false;
    }
    public function getProductTemplateBannerPrice($productTemplate, $priceProduct)
    {
        try {
            $publishedBanners = MainBanner1::where('is_published', true)
                ->where('is_banner', true)
                ->get();

            if ($priceProduct) {
                $shelfPrice = $priceProduct;
            } else {
                $shelfPrice = $productTemplate->list_price;
            }

            $bannerPrice = false;

            if ($publishedBanners) {
                foreach ($publishedBanners as $banner) {
                    foreach ($banner->item_ids as $line) {
                        if ($line->applied_on == '1_product' && $line->product_tmpl_id->id == $productTemplate->id) {
                            $bannerPrice = $shelfPrice - (($line->percent_price / 100) * $shelfPrice);
                            return $bannerPrice;
                        } elseif ($line->applied_on == '2_product_category') {
                            $category = ProductCategory::where('id', $line->categ_id->id)->first();
                            $productsIds = ProductTemplate::where('categ_id', $category->id)
                                ->get();

                            if ($productsIds->contains('id', $productTemplate->id)) {
                                $bannerPrice = $shelfPrice - (($line->percent_price / 100) * $shelfPrice);
                                return $bannerPrice;
                            }
                        } elseif ($line->applied_on == '3_global') {
                            $bannerPrice = $shelfPrice - (($line->percent_price / 100) * $shelfPrice);
                            return $bannerPrice;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public function getProductTemplatePromotionPrice($productTemplate, $priceProduct)
    {
        try {
            $publishedPromotions = ProductPriceList::where('is_published', true)
                ->where('is_promotion', true)
                ->get();

            if ($priceProduct) {
                $shelfPrice = $priceProduct;
            } else {
                $shelfPrice = $productTemplate->list_price;
            }

            $promotionPrice = false;

            if ($publishedPromotions) {
                foreach ($publishedPromotions as $promotion) {
                    foreach ($promotion->item_ids as $line) {
                        if ($line->applied_on == '1_product' && $line->product_tmpl_id->id == $productTemplate->id) {
                            $promotionPrice = $shelfPrice - (($line->percent_price / 100) * $shelfPrice);
                            return $promotionPrice;
                        } elseif ($line->applied_on == '2_product_category') {
                            $category = ProductCategory::where('id', $line->categ_id->id)->first();
                            $productsIds = ProductTemplate::where('categ_id', $category->id)
                                ->get();

                            if ($productsIds->contains('id', $productTemplate->id)) {
                                $promotionPrice = $shelfPrice - (($line->percent_price / 100) * $shelfPrice);
                                return $promotionPrice;
                            }
                        } elseif ($line->applied_on == '3_global') {
                            $promotionPrice = $shelfPrice - (($line->percent_price / 100) * $shelfPrice);
                            return $promotionPrice;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            return false;
        }
    }

    // public function sortPoints($company_id, $destinations)
    // {
    //     $company = ResCompany::where('id', $company_id)->first();

    //     $branche_latitude = $company->partner_id->partner_latitude;
    //     $branche_longitude = $company->partner_id->partner_longitude;

    //     $distances = [];
    //     foreach ($destinations as $destination) {
    //         $lat = $destination['lat'];
    //         $lng = $destination['lng'];
    //         $distance = $this->getDistance($lat, $lng, $branche_latitude, $branche_longitude);
    //         $distances[] = [$destination, $distance];
    //     }

    //     // Sort destinations by distance
    //     usort($distances, function ($a, $b) {
    //         return $a[1] <=> $b[1];
    //     });

    //     // Now, $distances contains the points sorted by distance from the origin
    //     return $distances;
    // }

    public function getDistance($lat, $lng, $branche_latitude, $branche_longitude)
    {
        $distance = -1;
        // $APIKey ::get('web_google_maps.google_maps_view_api_key');
        // $url = "https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=$lat,$lng&destinations=$branche_latitude,$branche_longitude&key=$APIKey";

        // try {
        //     $response = Http::get($url);
        //     $response_data = json_decode($response->body(), true);

        //     if ($response_data['status'] == 'OK') {
        //         $distance = $response_data['rows'][0]['elements'][0]['distance']['value'] / 1000;
        //     } else {
        //         $distance = -1;
        //     }
        // } catch (Exception $e) {
        //     $distance = -1;
        // }

        return $distance;
    }

    public function zoneOfPoint($lat, $lng)
    {
        $point = ['lat' => $lat, 'lng' => $lng];

        $companies = ResCompany::all();
        $companies_zones = [];

        foreach ($companies as $company) {
            $zones = ZoneZone::where('company_id', $company->id)->get();
            $zone_list = [];

            if ($zones) {
                foreach ($zones as $zone) {
                    $zones_coordinates = [];
                    $latLogs = LatitudeLongitude::where('zone_id', $zone->id)->get();

                    if ($latLogs) {
                        foreach ($latLogs as $latLog) {
                            $valCoord = [
                                'lat' => $latLog->latitude,
                                'lng' => $latLog->longitude,
                            ];

                            $zones_coordinates[] = $valCoord;
                        }
                    }

                    $valueCoordinate = [
                        'zone_id' => $zone->id,
                        'coordinates' => $zones_coordinates,
                    ];

                    $zone_list[] = $valueCoordinate;
                }
            }

            $values = [
                'company_id' => $company->id,
                'zones' => $zone_list,
            ];

            $companies_zones[] = $values;
        }

        foreach ($companies_zones as $branch) {
            $zones = $branch['zones'];
            $company_id = $branch['company_id'];

            foreach ($zones as $zone) {
                $points = $zone['coordinates'];
                $polygon = [];

                foreach ($points as $point1) {
                    $polygon[] = $point1;
                }

                if ($this->checkIfPointInsideZone($point, $polygon)) {
                    return $zone['zone_id'];
                }
            }
        }

        return -1;
    }

    public function checkIfPointInsideZone($point, $polygon)
    {
        $vertices = $polygon;
        $intersections = 0;
        $vertices_count = count($vertices);

        for ($i = 0; $i < $vertices_count; $i++) {
            $vertex1 = $vertices[$i];
            $vertex2 = $vertices[($i + 1) % $vertices_count];

            if (
                $vertex1['lat'] == $vertex2['lat']
                && $vertex1['lat'] == $point['lat']
                && $point['lng'] > min($vertex1['lng'], $vertex2['lng'])
                && $point['lng'] < max($vertex1['lng'], $vertex2['lng'])
            ) {
                return true;
            }

            $epsilon = 0.00001;
            if (
                abs($this->distance($point, $vertex1) + $this->distance($point, $vertex2) - $this->distance($vertex1, $vertex2))
                < $epsilon
            ) {
                return true;
            }

            if (
                $point['lat'] > min($vertex1['lat'], $vertex2['lat'])
                && $point['lat'] <= max($vertex1['lat'], $vertex2['lat'])
                && $point['lng'] <= max($vertex1['lng'], $vertex2['lng'])
                && $vertex1['lat'] != $vertex2['lat']
            ) {
                $xinters = ($point['lat'] - $vertex1['lat']) * ($vertex2['lng'] - $vertex1['lng'])
                    / ($vertex2['lat'] - $vertex1['lat']) + $vertex1['lng'];

                if ($vertex1['lng'] == $vertex2['lng'] || $point['lng'] <= $xinters) {
                    $intersections++;
                }
            }
        }

        if ($intersections % 2 != 0) {
            return true;
        } else {
            return false;
        }
    }

    public function distance($point1, $point2)
    {
        $lat1 = $point1['lat'];
        $lng1 = $point1['lng'];
        $lat2 = $point2['lat'];
        $lng2 = $point2['lng'];

        $theta = $lng1 - $lng2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = 1.609344; // Convert miles to kilometers
        $distance = $miles * $unit;

        return $distance;
    }


    public function getCompanyZones($company_id)
    {
        $company = ResCompany::where('id', $company_id)->first();
        if ($company) {
            $zones = ZoneZone::where('company_id', $company->id)->get();
            $zone_list = [];

            if ($zones) {
                foreach ($zones as $zone) {
                    $zones_coordinates = [];
                    $lat_logs = LatitudeLongitude::where('zone_id', $zone->id)->get();

                    if ($lat_logs) {
                        foreach ($lat_logs as $lat_log) {
                            $val_coord = [
                                'lat' => $lat_log->latitude,
                                'lng' => $lat_log->longitude,
                            ];
                            $zones_coordinates[] = $val_coord;
                        }
                    }

                    $value_coordinate = [
                        'zone_id' => $zone->id,
                        'coordinates' => $zones_coordinates,
                    ];

                    $zone_list[] = $value_coordinate;
                }
            }

            $values = [
                'company_id' => $company->id,
                'zones' => $zone_list,
            ];
        } else {
            $values = [];
        }

        return $values;
    }

    public function getCompanyZonesWithoutId($company_id = null)
    {
        $zone_list = [];

        if ($company_id) {
            $company = ResCompany::where('id', $company_id)->first();

            if ($company) {
                $zones = ZoneZone::where('company_id', $company->id)->get();
                $zone_list = [];

                if ($zones) {
                    foreach ($zones as $zone) {
                        $zones_coordinates = [];
                        $lat_logs = LatitudeLongitude::where('zone_id', $zone->id)->get();

                        if ($lat_logs) {
                            foreach ($lat_logs as $lat_log) {
                                $val_coord = [
                                    'lat' => $lat_log->latitude,
                                    'lng' => $lat_log->longitude,
                                ];
                                $zones_coordinates[] = $val_coord;
                            }
                        }

                        $value_coordinate = [
                            'coordinates' => $zones_coordinates,
                        ];

                        $zone_list[] = $value_coordinate;
                    }
                }
            }
        } else {
            $companies = ResCompany::all();

            if ($companies) {
                foreach ($companies as $company) {
                    $zones = ZoneZone::where('company_id', $company->id)->get();

                    if ($zones) {
                        foreach ($zones as $zone) {
                            $zones_coordinates = [];
                            $lat_logs = LatitudeLongitude::where('zone_id', $zone->id)->get();

                            if ($lat_logs) {
                                foreach ($lat_logs as $lat_log) {
                                    $val_coord = [
                                        'lat' => $lat_log->latitude,
                                        'lng' => $lat_log->longitude,
                                    ];
                                    $zones_coordinates[] = $val_coord;
                                }
                            }

                            $value_coordinate = [
                                'coordinates' => $zones_coordinates,
                            ];

                            $zone_list[] = $value_coordinate;
                        }
                    }
                }
            }
        }

        return $zone_list;
    }

    public function zonesWithoutId($company_id = null)
    {
        $values = $this->getCompanyZonesWithoutId($company_id);
        return $values;
    }
    public static function generateOrderName()
    {
        $lastOrder = SaleOrder::orderBy('id', 'desc')->first();
        if ($lastOrder) {
            return $lastOrder->name + 1;
        }
        return 0;
    }
    public static function getOrderName()
    {
        $lastOrder = SaleOrder::orderBy('id', 'desc')->first();
        if ($lastOrder) {
            return $lastOrder->name;
        }
        return 0;
    }

    public function getPreparationTime($order)
    {
        $maxPreparation = 0;

        if ($order) {
            foreach ($order->saleOrderLines as $line) {
                $newPreparation = $line->product->preparing_time ? $line->product->preparing_time : 0;

                if ($maxPreparation < $newPreparation) {
                    $maxPreparation = $newPreparation;
                }
            }

            return $maxPreparation;
        } else {
            return 0;
        }
    }
    public static  function isValidEmail($email)
    {
        // Regular expression pattern for basic email validation
        $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

        if (preg_match($pattern, $email)) {
            return true;
        } else {
            return false;
        }
    }
    public function calculForAddress($lat, $lng, $company_id = null)
    {
        $point = ['lat' => $lat, 'lng' => $lng];
        $detail = new CustomHelper();
        if ($company_id === null) {
            $companies = ResCompany::all();
        } else {
            $companies = ResCompany::where('id', $company_id)->get();
        }

        $companiesZones = [];
        foreach ($companies as $company) {
            $zones = ZoneZone::where('company_id', $company->id)->get();
            $zoneList = [];
            foreach ($zones as $zone) {

                $zonesCoordinates = [];
                $latLogs = LatitudeLongitude::where('zone_id', $zone->id)->get();

                if ($latLogs) {
                    foreach ($latLogs as $latLog) {
                        $valCoord = [
                            "lat" => $latLog->latitude,
                            "lng" => $latLog->longitude,
                        ];
                        $zonesCoordinates[] = $valCoord;
                    }
                }

                $valueCoordinate = [
                    "zone_id" => $zone->id,
                    "coordinates" => $zonesCoordinates,
                ];
                $zoneList[] = $valueCoordinate;
            }

            $values = [
                "company_id" => $company->id,
                "zones" => $zoneList,
            ];
            $companiesZones[] = $values;
        }

        foreach ($companiesZones as $branch) {
            $zones = $branch['zones'];
            $companyID = $branch['company_id'];


            foreach ($zones as $zone) {
                $points = $zone['coordinates'];
                $polygon = [];

                foreach ($points as $point1) {
                    $polygon[] = $point1;
                }

                if ($detail->checkIfPointInsideZone($point, $polygon)) {

                    $fees = $this->getFees($companyID, $zone['zone_id'], $point);
                    $values = [
                        'zone_id' => $zone['zone_id'],
                        'company_id' => $companyID,
                        'fees' => $fees,
                        'fees_without_TVA' => $this->getFeesWithoutTVA($fees),
                    ];
                    return $values;
                }
            }
        }
        return [];
    }
    public function getFeesWithoutTVA($fees)
    {
        $deliveryItem = ProductTemplate::where('is_delivery', true)->first();

        if ($deliveryItem) {
            try {
                $taxes = $deliveryItem->taxes;
                $amount = 0;

                foreach ($taxes as $tax) {
                    $amount += $tax->amount;
                }

                $feesWithoutTVA = round($fees / (1 + $amount / 100), 2);
            } catch (Exception $e) {
                $feesWithoutTVA = $fees;
            }

            return $feesWithoutTVA;
        } else {
            return $fees;
        }
    }

    public function getFees($companyID, $zoneID, $point)
    {
        $company = ResCompany::where('id', $companyID)->first();
        $detail = new CustomHelper();

        if ($company) {
            $feesType = $company->fees_type;

            if ($feesType == 'fixed') {
                if ($company->fixed_fees) {
                    return $company->fixed_fees;
                } else {
                    return 0;
                }
            } elseif ($feesType == 'by_distance') {
                if ($company->minimum_fees) {
                    $minimumFees = $company->minimum_fees;
                } else {
                    $minimumFees = 0;
                }

                if ($company->price_by_km) {
                    $priceByKm = $company->price_by_km;
                } else {
                    $priceByKm = 0;
                }

                $theDistance = $detail->getDistance($point['lat'], $point['lng'], $company->partner_id->partner_latitude, $company->partner_id->partner_longitude);

                $fees = $theDistance * $priceByKm;

                if ($fees >= $minimumFees) {
                    return $fees;
                } else {
                    return $minimumFees;
                }
            } else {
                $zone = ZoneZone::where('id', $zoneID)->first();

                if ($zone) {
                    if ($zone->delivery_fees) {
                        return $zone->delivery_fees;
                    } else {
                        return 0;
                    }
                } else {
                    return 0;
                }
            }
        } else {
            return 0;
        }
    }
    public function getDeliveryTime($company_id, $zone_id)
    {
        $company = ResCompany::find($company_id);

        if ($company) {
            $time_type = $company->delivery_time_type;

            if ($time_type == 'fixed') {
                return $company->fixed_time ?: 0;
            } else {
                $zone = ZoneZone::find($zone_id);

                if ($zone) {
                    return $zone->delivery_time ?: 0;
                } else {
                    return 0;
                }
            }
        } else {
            return 0;
        }
    }
}
