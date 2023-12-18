<?php

namespace App\Http\Controllers\Api\V1\Platform;

use App\Filament\Resources\AboutUsResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Models\Tenant\AboutUs;
use App\Models\Tenant\AboutUsMission;
use App\Models\Tenant\AboutUsSlider;
use App\Models\Tenant\AboutUsValue;
use App\Models\Tenant\AboutUsVision;
use App\Models\Tenant\CustomerFeedback;
use App\Models\MainBanner3;
use App\Models\ProductProduct;
use App\Models\Tenant\ResPartner;
use App\Models\Tenant\Team;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AboutusApiController extends Controller
{
    public function aboutus(Request $request)
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

            $about = AboutUs::where('company_id', $company)->first();

            $banner = $about->about_us_banner_attachment;
            if (!$banner) {
                $banner = "";
            } else {
                $banner = "/storage/" . $banner;
            }
            $image = $about->image_link_attachment;
            if (!$image) {
                $image = "";
            } else {
                $image = "/storage/" . $image;
            }
            $link = $about->links;
            if ($link)
                $link = true;
            else
                $link = false;

            if ($about) {
                return response()->json([
                    'response' => [
                        'id' => $about->id,
                        'name' => $about->name,
                        'description' => $about->description,
                        'banner' => $banner,
                        'links' => $link,
                        'video_url' => $about->video_url,
                        'image_link' => $image
                    ],
                    'message' => 'List Of About Us Found'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'List Of About Us Not Found'
                ], Response::HTTP_NOT_FOUND);
            }
        }
    }

    public function team(Request $request)
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

            $team = Team::where('company_id', $company)->first();
            //        dd($team);
            if ($team) {
                $partner_list = ResPartner::where('company_id', $company)
                    ->where('is_member', true)
                    ->get();
                if ($partner_list) {
                    $image = $team->team_image_attachment;
                    if (!$image) {
                        $image = "";
                    } else {
                        $image = "/storage/" . $image;
                    }
                    return response()->json([
                        'response' => [
                            'team_id' => $team->id,
                            'team_name' => $team->name,
                            'team_image' => $image,
                            'member_ids' => AboutUsResource::collection($partner_list),
                        ],
                        'message' => 'List Of Team Found'
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'response' => [],
                        'message' => 'List Of Member Not Found'
                    ], Response::HTTP_NOT_FOUND);
                }
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'List Of team Not Found'
                ], Response::HTTP_NOT_FOUND);
            }
        }
    }

    public function aboutus_slider(Request $request)
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

            $missionsliders = AboutUsSlider::where('company_id', $company)->get();
            if (!$missionsliders->isEmpty()) {
                foreach ($missionsliders as $missionslider) {
                    $image = $missionslider->about_us_slider_image_attachment;
                    if (!$image) {
                        $image = "";
                    } else {
                        $image = "/storage/" . $image;
                    }

                    $res = [
                        "id" => $missionslider->id,
                        "name" => $missionslider->name,
                        "image" => $image
                    ];
                    $result[] = $res;
                }
                return response()->json([
                    'response' =>  $result,
                    'message' => 'List Of About Slider Found'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'List Of About Slider Not Found'
                ], Response::HTTP_NOT_FOUND);
            }
        }
    }


    public function customer_feedback(Request $request)
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

            $customerfeedbacks = CustomerFeedback::where('company_id', $company)->get();
            //        dd($customerfeedbacks);
            if (!$customerfeedbacks->isEmpty()) {
                foreach ($customerfeedbacks as $customerfeedback) {
                    $image = $customerfeedback->customer_feedback_image_attachment;
                    if (!$image) {
                        $image = "";
                    } else {
                        $image = "/storage/" . $image;
                    }

                    $res = [
                        "feedback_id" => $customerfeedback->id,
                        "customer_name" => $customerfeedback->name,
                        "customer_comment" => $customerfeedback->customer_comment,
                        "customer_image" => $image
                    ];
                    $result[] = $res;
                }
                return response()->json([
                    'response' =>  $result,
                    'message' => 'List Of Customer Feedback Found'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'List Of Customer Feedback Not Found'
                ], Response::HTTP_NOT_FOUND);
            }
        }
    }


    public function aboutus_mission(Request $request)
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

            $aboutmission = AboutUsMission::where('company_id', $company)->first();
            if ($aboutmission) {
                $image = $aboutmission->about_us_slider_image_attachment;
                if (!$image) {
                    $image = "";
                } else {
                    $image = "/storage/" . $image;
                }
                return response()->json([
                    'response' => [
                        'id' => $aboutmission->id,
                        'name' => $aboutmission->name,
                        'description' => $aboutmission->description,
                        'image' => $image,
                    ],
                    'message' => 'List Of About Us Mission Found'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'List Of About Us Mission Not Found'
                ], Response::HTTP_NOT_FOUND);
            }
        }
    }

    public function aboutus_vision(Request $request)
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

            $aboutvision = AboutUsVision::where('company_id', $company)->first();
            if ($aboutvision) {

                return response()->json([
                    'response' => [
                        'id' => $aboutvision->id,
                        'name' => $aboutvision->name,
                        'description' => $aboutvision->description,
                        'image' => $aboutvision->about_us_slider_image_attachment,
                    ],
                    'message' => 'List Of About Us Vision Found'

                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'List Of About Us Vision Not Found'
                ], Response::HTTP_NOT_FOUND);
            }
        }
    }

    public function aboutus_value(Request $request)
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

            $aboutvalue = AboutUsValue::where('company_id', $company)->first();
            if ($aboutvalue) {
                $image = $aboutvalue->about_us_slider_image_attachment;
                if (!$image) {
                    $image = "";
                } else {
                    $image = "/storage/" . $image;
                }

                return response()->json([
                    'response' => [
                        'id' => $aboutvalue->id,
                        'name' => $aboutvalue->name,
                        'description' => $aboutvalue->description,
                        'image' => $image,
                    ],
                    'message' => 'List Of About Us Value Found'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'List Of About Us Value Not Found'
                ], Response::HTTP_NOT_FOUND);
            }
        }
    }
}
