<?php

namespace App\Http\Controllers\Api\V1\Platform;

use App\Http\Controllers\Controller;

use App\Models\ProductCategory;

use App\Models\ProductProduct;
use App\Models\Tenant\ProductTemplate;
use App\Models\Tenant\ProductWishlist;
use App\Models\Tenant\ResCompany;
use App\Models\Tenant\User;
use App\Utils\CustomHelper;
use App\Utils\Lang;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function getProductInformationById(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'product_tmpl_id' => 'required|integer',
        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        }
        $xLocalization = $request->header('x-localization');
        $lang = 'en';

        if ($xLocalization && $xLocalization === 'ar') {
            $lang = 'ar';
        }
        // $request->validate([
        //     'product_tmpl_id' => 'required',
        // ]);
        $productTemplateId = $request->input('product_tmpl_id');
        $product = ProductTemplate::find($productTemplateId);
        if ($product) {
            $isFav = false;
            $details = new CustomHelper();

            $image = $product->image;
            // Define how you want to determine if it's a favorite

            $user_id = $request->input('user_id');

            if ($user_id) {
                $retailerUser = User::find($user_id);
            } else {
                $retailerUser = null;
            }

            if ($retailerUser) {
                $allWishlist = ProductWishlist::where([
                    ['user_id', '=', $user_id],
                    // ['product_id.product_tmpl_id', '=', $productTemplateId]
                ])
                    ->whereHas('productProduct', function ($query) use ($productTemplateId) {
                        $query->where('product_tmpl_id', '=', $productTemplateId);
                    })
                    ->get();
                if (!$allWishlist->isEmpty()) {
                    $isFav = true;
                }
            } else {
                $allWishlist = null;
            }

            if ($product->tax_included) {
                try {
                    $tva = 0;
                    $priceProduct = $product->list_price;

                    if ($product->company) {

                        $tva = $product->company->tax;
                    } else {

                        $tva = ResCompany::first()->tax;
                    }

                    // $res = $productProduct->taxesId->computeAll($price, $productProduct);
                    $priceProduct = round($priceProduct + ($tva * $priceProduct / 100), 2);
                } catch (\Exception $e) {
                    $priceProduct = $product->list_price;
                }
            } else {
                $priceProduct = $product->list_price;
            }
            if ($product->is_combo)
                $combo = true;
            else
                $combo = false;
            $values = [
                "product_tmpl_id" => $product->id,
                "product_name" => Lang::get_name($product->name, $lang),
                "product_description" => Lang::get_name($product->description, $lang),
                "product_main_image" => '/storage/' . $image,
                "product_images" => '/storage/' . $image,
                'is_fav' => $isFav,
                'is_combo' => $combo,
                'template_sale_price_old' => $priceProduct,
                'template_sale_price_new' => $details->getProductTemplatePrice($product),
                'product_template_details' => $details->getProductTemplateDetails($product, $lang),
                'Addons_details' => $details->getAddOns($product, $lang),
                'Ingredients_details' => $details->getIngredients($product, $lang),
                'Removable_Ingredients_details' => $details->getRemovableIngredients($product, $lang),
                'Drinks_caption' => Lang::get_name($product->drinks_caption, $lang),
                'Drinks_details' => $details->getDrinksProduct($product, $lang),
                'Sides_caption' => Lang::get_name($product->sides_caption, $lang),
                'Sides_Products_details' => $details->getSidesProduct($product, $lang),
                'Related_caption' => Lang::get_name($product->related_caption, $lang),
                'Related_Products_details' => $details->getRelatedProduct($product, $lang),
                'Also_Like_caption' => Lang::get_name($product->liked_caption, $lang),
                'Also_Like_Products_details' => $details->getAlsoLikeProduct($product, $lang),
                'Desserts_caption' => Lang::get_name($product->desserts_caption, $lang),
                'Desserts_Products_details' => $details->getDessertsProduct($product, $lang),
                'Product_attributes' => $details->getAttributeProductNew($product, $lang),
                'Product_contents' => $details->getProductContents($product, $lang),
            ];
            return response()->json([
                'response' => $values,
                'message' => 'Product Info'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'response' => [],
                'message' => ' No Data Found'
            ], Response::HTTP_NOT_FOUND);
        }
    }
    public function getListOfProducts(Request $request)
    {
        // $validate = Validator::make($request->all(), [
        //     'user_id' => 'required|integer',
        // ]);
        // if ($validate->fails()) {
        //     $errors = $validate->errors();
        //     return response()->json([
        //         'response' => $errors,
        //         'message' => 'An error Occurred!'
        //     ], Response::HTTP_NOT_FOUND);
        // }
        $xLocalization = request()->header('x-localization');
        $lang = "en";

        if ($xLocalization) {
            if ($xLocalization == 'ar') {
                $lang = "ar";
            }
        }

        $products = ProductTemplate::where('app_publish', true)->get();

        $list = [];
        $ProductInfo = new CustomHelper();

        $user_id = $request->input('user_id');

        if ($user_id) {
            $retailerUser = User::find($user_id);
        } else {
            $retailerUser = null;
        }
        if ($products) {
            foreach ($products as $product) {
                $isFav = false;
                $product_id = $product->id;
                if ($retailerUser) {
                    $allWishlist = ProductWishlist::where([
                        ['user_id', '=', $user_id],
                        // ['product_id.product_tmpl_id', '=', $productTemplateId]
                    ])
                        ->whereHas('productProduct', function ($query) use ($product_id) {
                            $query->where('product_tmpl_id', '=', $product_id);
                        })
                        ->get();
                    if (!$allWishlist->isEmpty()) {
                        $isFav = true;
                    }
                } else {
                    $allWishlist = null;
                }

                $values = [
                    "product_id" => $product->id,
                    "product_name" => Lang::get_name($product->name, $lang),
                    "product_image" => $product->image,
                    "price" => $ProductInfo->getProductTemplatePrice($product),
                    "is_fav" => $isFav,
                ];
                $list[] = $values;
            }

            return response()->json([
                'response' => $list,
                'message' => 'list of products'
            ], Response::HTTP_OK);
        } else {

            return response()->json([
                'response' => [],
                'message' => 'no products found'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function searchProduct(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'product_name' => 'required',
            'company_id' => 'required|integer',
        ]);
        if ($validate->fails()) {

            return response()->json([
                'response' => [],
                'message' => 'Product name or Company id not send'
            ], Response::HTTP_NOT_FOUND);
        }
        $request->validate([
            'product_name' => 'required',
            'company_id' => 'required|integer',
        ]);
        $product_name = $request->input('product_name');
        $company_id = $request->input('company_id');

        $user_id = $request->input('user_id');
        if ($user_id) {
            $retailerUser = User::find($user_id);
        } else {
            $retailerUser = null;
        }
        // dd($retailerUser);

        // $results = ProductTemplate::where('company_id', $company_id)
        //     ->where(function ($query) use ($product_name) {
        //         // $query->whereRaw("LOWER(name) LIKE ?", ['%' . strtolower($product_name) . '%']);
        //         // ->orWhere("LOWER(name->ar) LIKE ?", ['%' . strtolower($product_name) . '%']);

        //         // $query->where(DB::raw("(name)"), 'like', '%' . $product_name . '%');
        //         $query->where('name', 'like', '%' . $product_name . '%');
        //         //select * from product_templates where Lower(name) like Lower('%pIzza%');
        //     })
        //     ->get();
        $results = ProductTemplate::where('company_id', $company_id)->whereRaw('LOWER(name) like LOWER(?)', ['%' . $product_name . '%'])->get();

        if ($results->isEmpty()) {
            return response()->json([
                'response' => [],
                'message' => 'No matching products found'
            ], Response::HTTP_NOT_FOUND);
        } else {
            $formattedResults = $results->map(function ($product) use ($retailerUser, $user_id) {

                $isFav = false;
                $product_id = $product->id;
                // dd($retailerUser);

                if ($retailerUser) {
                    $allWishlist = ProductWishlist::where([
                        ['user_id', '=', $user_id],
                        // ['product_id.product_tmpl_id', '=', $productTemplateId]
                    ])
                        ->whereHas('productProduct', function ($query) use ($product_id) {
                            $query->where('product_tmpl_id', '=', $product_id);
                        })
                        ->get();
                    if (!$allWishlist->isEmpty()) {
                        $isFav = true;
                    }
                } else {
                    $allWishlist = null;
                }
                return [
                    'product_id' => $product->id,
                    'product_name' => $product->name['en'],
                    'product_image' => "/storage/" . $product->image,
                    'price' => $product->list_price,
                    'is_fav' => $isFav,
                    'category_id' => $product->categ_id,
                ];
            });

            return response()->json([
                'response' => $formattedResults,
                'message' => 'list of products matching the search criteria'
            ], Response::HTTP_OK);
        }
    }
}
