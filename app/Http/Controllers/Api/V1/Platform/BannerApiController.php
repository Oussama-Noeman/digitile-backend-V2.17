<?php

namespace App\Http\Controllers\Api\V1\Platform;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Http\Resources\ProductResource;
use App\Models\Tenant\MainBanner1;
use App\Models\Tenant\MainBanner2;
use App\Models\Tenant\MainBanner3;
use App\Models\Tenant\MainPageSection;
use App\Models\ProductProduct;
use App\Models\Tenant\ProductTemplate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BannerApiController extends Controller
{
    public function index1(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'company_id' => 'required|integer',
        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        } else {

            $rawData = $request->getContent();
            $jsonData = json_decode($rawData);
            $company = $jsonData->company_id;

            $banner = MainBanner1::where('company_id', $company)->get();
            if (!$banner->isEmpty()) {

                return response()->json([
                    'response' => BannerResource::collection($banner),
                    'message' => 'List Of main page banners1 Found'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'List Of main page banners1 Not Found'
                ], Response::HTTP_NOT_FOUND);
            }
        }
    }

    public function index2(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'company_id' => 'required|integer',
        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        } else {

            $rawData = $request->getContent();
            $jsonData = json_decode($rawData);
            $company = $jsonData->company_id;

            $banner = MainBanner2::where('company_id', $company)->get();
            if (!$banner->isEmpty()) {

                return response()->json([
                    'response' => BannerResource::collection($banner),
                    'message' => 'List Of main page banners2 Found'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'response' => [], 'message' => 'List Of main page banners2 Not Found'
                ], Response::HTTP_NOT_FOUND);
            }
        }
    }

    public function index3(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'company_id' => 'required|integer',
        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        } else {

            $rawData = $request->getContent();
            $jsonData = json_decode($rawData);
            $company = $jsonData->company_id;

            $banner = MainBanner3::where('company_id', $company)->get();
            if (!$banner->isEmpty()) {

                return response()->json([
                    'response' => BannerResource::collection($banner),
                    'message' => 'List Of main page banners3 Found'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'List Of main page banners3 Not Found'
                ], Response::HTTP_NOT_FOUND);
            }
        }
    }
    public function page_section(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'number' => 'required|integer',
            'company_id' => 'required|integer',
        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        }
        $section = $request->input('number');
        $company = $request->input('company_id');

        $pagesections = MainPageSection::where('section_number', $section)
            ->where('company_id', $company)
            ->first();
        if ($pagesections) {
            $products_id = DB::table('product_main_page_section_rels')
                ->where('main_page_section_id', $pagesections->id)
                ->pluck('product_id');

            $product_list = ProductTemplate::whereIn('id', $products_id)
                ->where('active', 1)
                ->where('app_publish', true)
                ->get();
            // dd($products_id);
            $image = $pagesections->image;
            if (!$image) {
                $image = "";
            } else {
                $image = "/storage/" . $image;
            }
            return response()->json([
                'response' => [
                    'id' => $pagesections->id,
                    'name' => $pagesections->name['en'],
                    'image' => $image,
                    'product_ids' => ProductResource::collection($product_list)
                ],
                'message' => 'List Of main page section Found'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'response' => [],
                'message' => 'List Of main page section Not Found'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function getMainBanner(Request $request, $company_id)
    {
        $banner = MainBanner1::where('company_id', $company_id)->get();
        if (!$banner->isEmpty()) {

            return response()->json([
                'response' => BannerResource::collection($banner),
                'message' => 'List Of main page banners1 Found'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'response' => [],
                'message' => 'List Of main page banners1 Not Found'
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
