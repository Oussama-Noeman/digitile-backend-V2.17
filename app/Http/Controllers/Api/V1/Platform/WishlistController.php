<?php

namespace App\Http\Controllers\Api\V1\Platform;

use App\Http\Controllers\Controller;
use App\Models\Tenant\ProductProduct;
use App\Models\Tenant\ProductTemplate;
use App\Models\Tenant\ProductWishlist;
use App\Models\Tenant\User;
use App\Utils\CustomHelper;
use App\Utils\Lang;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class WishlistController extends Controller
{
    public function getWishlist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        }
        $requestData = $request->all();
        $userId = $requestData['user_id'] ?? null;
        $xLocalization = request()->header('x-localization');
        $lang = "en";

        if ($xLocalization) {
            if ($xLocalization == 'ar') {
                $lang = "ar";
            }
        }


        $retailerUser = User::find($userId);

        if ($retailerUser) {
            $allWishlist = ProductWishlist::where('user_id', $userId)->get();
            $wishList = [];
            // dd($allWishlist);

            foreach ($allWishlist as $wish) {
                $productInfo = new CustomHelper();
                $values = [
                    'product_id' => $wish->product_template_id,
                    'product_name' => Lang::get_name($wish->productTemplate->name, $lang),
                    'product_image' => '/web/content/' . $wish->productTemplate->image,
                    'price' => $productInfo->getProductTemplatePrice($wish->productTemplate),
                    //                        'price' => 0,
                    // $productInfo->getProductTemplatePrice($wish->productTemplate),
                    'is_fav' => true,
                ];

                $wishList[] = $values;
            }
            return response()->json([
                'response' => $wishList,
                'message' => 'Wishlist Found'
            ], Response::HTTP_OK);
        } else {

            return response()->json([
                'message' => 'User Not Found!'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function addToWishlist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'product_id' => 'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        }
        $requestData = $request->all();
        $userId = $requestData['user_id'] ?? null;

        if ($userId) {
            $retailerUser = User::find($userId);

            if ($retailerUser) {
                $productId = $requestData['product_id'] ?? null;

                if ($productId) {
                    $product = ProductTemplate::find($productId);

                    if ($product) {
                        $existingWishlist = ProductWishlist::where('user_id', $userId)
                            ->where('product_template_id', $product->id)
                            ->first();

                        if ($existingWishlist) {
                            return response()->json([
                                'message' => 'Product already added to wishlist!'
                            ], Response::HTTP_NOT_FOUND);
                        }
                        $product_product = ProductProduct::where('product_tmpl_id', $product->id)->where('active', true)
                            ->first();
                        $wishlistCreated = ProductWishlist::create([
                            'user_id' => $userId,
                            'product_id' => $product_product->id,
                            'product_template_id' => $product->id,
                            'website_id' => 1,
                        ]);

                        if ($wishlistCreated) {
                            return response()->json([
                                'response' => $wishlistCreated->id,
                                'message' => 'Wishlist Created'
                            ], Response::HTTP_OK);
                        } else {
                            return response()->json([
                                'message' => 'Not Added!'
                            ], Response::HTTP_NOT_FOUND);
                        }
                    } else {
                        return response()->json([
                            'message' => 'Product Not Found!'
                        ], Response::HTTP_NOT_FOUND);
                    }
                } else {
                    return response()->json([
                        'message' => 'Product ID is required!'
                    ], Response::HTTP_NOT_FOUND);
                }
            } else {
                return response()->json([
                    'message' => 'User Not Found!'
                ], Response::HTTP_NOT_FOUND);
            }
        } else {
            return response()->json([
                'message' => 'User ID is required!'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function removeFromWishlist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'product_id' => 'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        }
        $requestData = $request->all();
        $userId = $requestData['user_id'] ?? null;

        if ($userId) {
            $retailerUser = User::find($userId);

            if ($retailerUser) {
                $productId = $requestData['product_id'] ?? null;

                if ($productId) {
                    $productWishlist = ProductWishlist::where('product_template_id', $productId)
                        ->where('user_id', $userId)
                        ->first();

                    if ($productWishlist) {
                        $productWishlist->delete();
                        return response()->json([

                            'message' => 'Product removed from wishlist!'
                        ], Response::HTTP_OK);
                    } else {
                        return response()->json([
                            'message' => 'Product Not Found!'
                        ], Response::HTTP_NOT_FOUND);
                    }
                } else {
                    return response()->json([
                        'message' => 'Product ID is required!'
                    ], Response::HTTP_NOT_FOUND);
                }
            } else {
                return response()->json([
                    'message' =>  'User Not Found!'
                ], Response::HTTP_NOT_FOUND);
            }
        } else {
            return response()->json([
                'message' => 'User ID is required!'
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
