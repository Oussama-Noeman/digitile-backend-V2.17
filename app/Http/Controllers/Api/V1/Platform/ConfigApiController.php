<?php

namespace App\Http\Controllers\Api\V1\Platform;

use App\Http\Controllers\Controller;
use App\Models\Tenant\ResCompany;
use App\Models\Tenant\ResourceCalendar;
use App\Models\Tenant\ResourceCalendarAttendance;
use App\Utils\CustomHelper;
use Carbon\Carbon;
use Faker\Provider\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use function Laravel\Prompts\password;
use http\Env;

class ConfigApiController extends Controller
{
    public function configuration(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'company_id' => 'required',
        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        }

        $id = $request->input('company_id');
        $company = ResCompany::where('id', $id)->first();
        $social = [
            "twitter" => $company->social_twitter ?? "",
            "facebook" => $company->social_facebook ?? "",
            "gitHub" => $company->social_github ?? "",
            "linkedIn" => $company->social_linkedin ?? "",
            "youtube" => $company->social_youtube ?? "",
            "instagram" => $company->social_instagram ?? "",
            "whatsapp" => $company->whatsapp ?? ""
        ];
        $banners = [];
        $dealbanner = $company->deal_banner_image_attachment;
        if (!$dealbanner) {
            $dealbanner = "";
        } else {
            $dealbanner = "/storage/" . $dealbanner;
        }

        $dealbackground = $company->deal_background_image_attachment;
        if (!$dealbackground)
            $dealbackground = '';
        else
            $dealbackground = "/storage/" . $dealbackground;

        $categorybanner = $company->category_image_attachment;
        if (!$categorybanner)
            $categorybanner = '';
        else
            $categorybanner = "/storage/" . $categorybanner;

        $cartbanner = $company->cart_image_attachment;
        if (!$cartbanner)
            $cartbanner = '';
        else
            $cartbanner = "/storage/" . $cartbanner;

        $checkout = $company->checkout_image_attachment;
        if (!$checkout)
            $checkout = '';
        else
            $checkout = "/storage/" . $checkout;

        $sign = $company->sign_banner_attachment;
        if (!$sign)
            $sign = '';
        else
            $sign = "/storage/" . $sign;

        $faqbanner = $company->faq_banner;
        if (!$faqbanner)
            $faqbanner = '';
        else
            $faqbanner = "/storage/" . $faqbanner;

        $carreer = $company->career_banner;
        if (!$carreer)
            $carreer = '';
        else
            $carreer = "/storage/" . $carreer;


        $banners = [
            "category_banner" => $categorybanner,
            "category_title" => $company->category_title ?? "",
            "cart_title" => $company->cart_title ?? "",
            "cart_banner" => $cartbanner,
            "checkout_title" => $company->checkout_title ?? "",
            "checkout_banner" => $checkout,
            "faq_banner" => $faqbanner,
            "career_banner" => $carreer,
            "deal_title1" => $company->deal_title1 ?? "",
            "deal_title2" => $company->deal_title2 ?? "",
            "deal_banner" => $dealbanner,
            "deal_background" => $dealbackground
        ];
        $image = $company->image;
        if (!$image)
            $image = '';
        else
            $image = env('APP_URL') . "/storage/" . $image;

        if ($company->tax_included == 1)
            $tax = true;
        else
            $tax = false;

        if ($company->has_pickup == 1)
            $pick = true;
        else
            $pick = false;

        if ($company->has_delivery == 1)
            $dev = true;
        else
            $dev = false;

        $detail = new CustomHelper();
        $calendar = ResourceCalendar::where('company_id', $company->id)
            ->where('active', true)
            ->first();

        $restaurantScheduleTime = [];

        if ($calendar) {
            //            $dayOfWeekList = [0, 1, 2, 3, 4, 5, 6];
            $dayOfWeekName = [

                0 => 'Monday',
                1 => 'Tuesday',
                2 => 'Wednesday',
                3 => 'Thursday',
                4 => 'Friday',
                5 => 'Saturday',
                6 => 'Sunday',
            ];

            $restaurantScheduleTime = [];

            foreach ($dayOfWeekName as $dayOfWeek => $day) {
                $openClose = [];
                $calendarAttendance = ResourceCalendarAttendance::where('calendar_id', $calendar->id)
                    ->where('dayofweek', $dayOfWeek)
                    ->orderBy('dayofweek', 'ASC')
                    ->orderBy('hour_from', 'ASC')
                    ->get();

                if ($calendarAttendance->isNotEmpty()) {
                    foreach ($calendarAttendance as $att) {
                        $hour_from_parset = Carbon::createFromFormat('H:i:s', $att->hour_from)->format('G') + Carbon::createFromFormat('H:i:s', $att->hour_from)->format('i') / 60;
                        $hour_to_parset = Carbon::createFromFormat('H:i:s', $att->hour_to)->format('G') + Carbon::createFromFormat('H:i:s', $att->hour_to)->format('i') / 60;

                        $val = [
                            "opening_time" => $detail->formatTimeFromFloat($hour_from_parset),
                            "closing_time" => $detail->formatTimeFromFloat($hour_to_parset)
                        ];
                        $openClose[] = $val;
                    }

                    $valuesAtt = [
                        "day_name" =>  $day,
                        "day" => $dayOfWeek <= 6 ? $dayOfWeek : 0,
                        "opening_closing_time" => $openClose
                    ];

                    $restaurantScheduleTime[] = $valuesAtt;
                }
            }
        }
        $result = [
            "company_id" => $id,
            "restaurant_name" => ($company->name) ?? "",
            "restaurant_address" => ($company->street) ?? "",
            "restaurant_phone" => $company->phone ?? "",
            "restaurant_email" => $company->email ?? "",
            "currency_symbol" =>  is_object($company->resCurrency) ? $company->resCurrency->symbol["ar"] : "",
            "currency_symbol_en" => is_object($company->resCurrency) ? $company->resCurrency->symbol["en"] : "",
            "currency_id" => $company->resCurrency->id,
            "restaurant_logo" => $image,
            "restaurant_schedule_time" => $restaurantScheduleTime,
            "social_media_link" => $social,
            "company_banners" => [$banners],
            "sign-in-up-banner" => $sign,
            "tva_enable" => $tax,
            "has_pickup" => $pick,
            "has_delivery" => $dev,

        ];

        return response()->json([
            'response' => $result,
            'message' => 'Config Found'
        ], Response::HTTP_OK);
    }

    public function legal_information(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'company_id' => 'required',
        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        }
        $id = $request->input('company_id');
        $company = ResCompany::where('id', $id)->first();

        return response()->json([
            'response' => [
                'terms_and_conditions' => $company->terms_and_conditions ?? "",
                'privacy_policy' => $company->privacy_policy ?? "",
                'support' => $company->support ?? "",
            ],
            'message' => 'Legal Information Found'
        ], Response::HTTP_OK);
    }
}
