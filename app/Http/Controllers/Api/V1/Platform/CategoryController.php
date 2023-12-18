<?php

namespace App\Http\Controllers\Api\V1\Platform;

use App\Http\Controllers\Controller;
use App\Models\Tenant\ProductCategory;
use App\Models\ProductProduct;
use App\Models\Tenant\ProductTemplate;
use App\Models\Tenant\ProductWishlist;
use App\Models\Tenant\User;
use App\Utils\CustomHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function getCategoriesByCompany(Request $request)
    {
        $data = $request->all();
        if (isset($data[('company_id')])) {
            $company = $data[('company_id')];
            $categories = ProductCategory::where('company_id', $company)->where('is_publish', true)
                ->select('id', 'name', 'company_id', 'parent_id', 'created_at', 'updated_at', 'image', 'banner_image')
                ->get()
                ->toArray();
            if (!empty($categories)) {
                foreach ($categories as &$category) {
                    $category['position'] = 0;
                    $category['status'] = 1;
                    $category['slug'] = Str::slug($category['name']['en']);
                    $category['name'] = $category['name']['en'];
                    $category['image'] = "/storage/" . $category['image'];
                    $category['banner_image'] = "/storage/" . $category['banner_image'];
                }
                return response()->json([
                    'response' => $categories,
                    'message' => 'Categories found'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'Categories not found!'
                ], Response::HTTP_NOT_FOUND);
            }
        } else {
            return response()->json([
                'response' => [],
                'message' => 'No Comapny Defined!'
            ], Response::HTTP_NOT_FOUND);
        }
    }
    public function getAllCategoryProducts(Request $request)
    {
        $data = $request->all();
        $details = new CustomHelper();
        if (isset($data[('category_id')])) {
            $category = $data[('category_id')];
            $products = ProductTemplate::where('categ_id', $category)
                ->where('active', 1)
                ->select('id', 'name', 'image', 'list_price')
                ->get();
            //                ->toArray();
            $isFav = false;
            $user_id = $request->input('user_id');
            if (!empty($products)) {
                foreach ($products as &$product) {
                    // dd($product);
                    $productTemplateId = $product['id'];
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
                        } else $isFav = false;
                    } else {
                        $allWishlist = null;
                    }
                    $product['product_id'] = $product['id'];
                    $product['product_name'] = $product['name']['en'];
                    $product['product_image'] = "/storage/" . $product['image'];
                    $product['price'] = $details->getProductTemplatePrice($product); //$product['list_price'];
                    $product['is_fav'] = $isFav;
                    unset($product['id']);
                    unset($product['name']);
                    unset($product['image']);
                    unset($product['lst_price']);
                }
                return response()->json([
                    'response' => $products,
                    'message' => 'Products found'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'Products not found!'
                ], Response::HTTP_NOT_FOUND);
            }
        } else {
            return response()->json([
                'response' => [],
                'message' => 'No Category Defined!'
            ], Response::HTTP_NOT_FOUND);
        };
    }
}
