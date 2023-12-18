<?php

namespace App\Http\Controllers\Api\V1\Platform;

use App\Http\Controllers\Controller;
use App\Models\Tenant\DriverOrder;

use App\Models\OrdersTrip;
use App\Models\Tenant\ResCompany;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Tenant\ProductProduct;
use App\Models\Tenant\ResourceCalendar;
use App\Models\Tenant\ResourceCalendarAttendance;
use App\Models\Tenant\SaleOrder;
use App\Models\User;

use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use App\Utils\CustomHelper;
use Illuminate\Support\Facades\Validator;

class DriverOrderController extends Controller
{
    public function recordLocationData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer',
            'latitude' => 'required',
            'longitude' => 'required',
            'location' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        }
        $user = auth()->user(); // Assuming you have authentication set up
        if ($user) {
            $requestData = $request->all();
            $order_id = $requestData['order_id'];
            $driver_id = $user->partner; // Adjust this to match your Laravel model structure
            $latitude = $requestData['latitude'];
            $longitude = $requestData['longitude'];
            $location = $requestData['location'];

            // Assuming you have an Eloquent model named DriverOrderLocationData
            $order_location = DriverOrder::create([
                'driver_id' => $driver_id,
                'order_id' => $order_id,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'location' => $location,
            ]);

            // Example response
            $response = ['status' => 200, 'message' => 'Location Received'];
        } else {
            $response = ['status' => 404, 'message' => 'No User Found'];
        }

        return response()->json($response);
    }
    public function getRecordLocationData(Request $request)
    {
        $data = $request->all();
        if (isset($data['order_id'])) {
            $orderId = $data['order_id'];

            $orderLocations = DB::table('driver_orders')
                ->select('latitude', 'longitude', 'location')
                ->where('order_id', $orderId)
                ->get();

            if ($orderLocations->isNotEmpty()) {

                return response()->json([
                    'response' => $orderLocations,
                    'message' => 'Location Found'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'Location Not found'
                ], Response::HTTP_NOT_FOUND);
            }
        } else {
            return response()->json([
                'response' => [],
                'message' => 'Order is not defined'
            ], Response::HTTP_NOT_FOUND);
        }
    }



    public function getBranchesInformation()
    {
        $detail = new CustomHelper();
        $branches = ResCompany::all();
        $branchList = [];

        foreach ($branches as $branch) {
            $address = "";
            if ($branch->city) {
                $address = $branch->city;
            }
            if ($branch->street) {
                if ($address != "") {
                    $address .= ' , ' . $branch->street;
                } else {
                    $address = $branch->street;
                }
            }
            if ($branch->street2) {
                if ($address != "") {
                    $address .= ' , ' . $branch->street2;
                } else {
                    $address = $branch->street2;
                }
            }
            // dd($branch);
            $values = [
                "id" => $branch->id,
                "name" => $branch->name,
                "email" => $branch->email,
                "longitude" => $branch->resPartner->partner_longitude,
                "latitude" => $branch->resPartner->partner_latitude,
                "address" => $address,
                "coverage" => 0,
                "zones" => $detail->getCompanyZonesWithoutId($branch->id),
            ];

            $branchList[] = $values;
        }

        return $branchList;
    }

    public function getConfig(Request $request)
    {

        $detail = new CustomHelper();

        try {
            $requestData = $request->all();
            $company_id = $requestData['company_id'] ?? null;

            if ($company_id) {
                $restaurant = ResCompany::find($company_id);
            } else {
                $restaurant = ResCompany::where('parent_id', null)->orderBy('id')->first();
            }
        } catch (\Exception $e) {
            $restaurant = ResCompany::where('parent_id', null)->orderBy('id')->first();
        }

        if ($restaurant) {
            // $tax = \App\Tax::first();
            // $amount_tax = $tax ? $tax->amount : 0;
            $amount_tax = ResCompany::first()->tax;
            // $calendar = \App\ResourceCalendar::where('company_id', $restaurant->id)
            //     ->where('active', true)->first();

            // $restaurant_schedule_time = [];
            // if ($calendar) {
            //     $calendar_attendance = \App\ResourceCalendarAttendance::where('calendar_id', $calendar->id)
            //         ->orderBy('dayofweek', 'ASC')
            //         ->orderBy('hour_from', 'ASC')
            //         ->get();

            //     foreach ($calendar_attendance as $att) {
            //         $values_att = [
            //             "day_name" => $att->getDayOfWeek(),
            //             "day" => (int)$att->dayofweek <= 6 ? (int)$att->dayofweek : 0,
            //             "opening_time" => $detail->formatTimeFromFloat($att->hour_from),
            //             "closing_time" => $detail->formatTimeFromFloat($att->hour_to),
            //         ];

            //         $restaurant_schedule_time[] = $values_att;
            //     }
            // }
            // dd($restaurant);
            $restaurant_schedule_time[] = 0;
            $values = [
                "company_id" => $restaurant->id,
                "restaurant_name" => $restaurant->name,
                "restaurant_open_time" => "",
                "restaurant_close_time" => "",
                "restaurant_address" => $restaurant->street,
                "restaurant_phone" => $restaurant->phone,
                "restaurant_email" => $restaurant->email,
                "currency_symbol" => $restaurant->resCurrency->name,
                "currency_symbol_en" => $restaurant->resCurrency->name,
                "currency_id" => $restaurant->resCurrency->id,
                "restaurant_logo" => $restaurant->logo_web_attachment ? "/web/content/" . $restaurant->logo_web_attachment->id : "",
                "restaurant_logo_dark" => "",
                "restaurant_schedule_time" => $restaurant_schedule_time,
                "restaurant_location_coverage" => $detail->getCompanyZonesWithoutId(),
                "minimum_order_value" => 1,
                "base_urls" => [],
                "tax_percent" => $amount_tax,
                "delivery_charge" => 0,
                "delivery_management" => [
                    "status" => 1,
                    "min_shipping_charge" => 0,
                    "shipping_per_km" => 0,
                ],
                "cash_on_delivery" => "true",
                "digital_payment" => "true",
                "branches" => $this->getBranchesInformation(),
                "terms_and_conditions" => $restaurant->terms_and_conditions ?? "",
                "privacy_policy" => $restaurant->privacy_policy ?? "",
                "support" => $restaurant->support ?? "",
                "email_verification" => "",
                "phone_verification" => "",
                "currency_symbol_position" => $restaurant->resCurrency->position == "before" ? "left" : "right",
                "maintenance_mode" => "",
                "country" => "",
                "self_pickup" => "true",
                "delivery" => "true",
                "social_media_link" => [
                    [
                        "id" => 1,
                        "name" => "twitter",
                        "link" => $restaurant->social_twitter ?? "",
                        "status" => 1,
                        "created_at" => "",
                        "updated_at" => "",
                    ],
                    [
                        "id" => 2,
                        "name" => "facebook",
                        "link" => $restaurant->social_facebook ?? "",
                        "status" => 1,
                        "created_at" => "",
                        "updated_at" => "",
                    ],
                    [
                        "id" => 3,
                        "name" => "gitHub",
                        "link" => $restaurant->social_github ?? "",
                        "status" => 1,
                        "created_at" => "",
                        "updated_at" => "",
                    ],
                    [
                        "id" => 4,
                        "name" => "linkedIn",
                        "link" => $restaurant->social_linkedin ?? "",
                        "status" => 1,
                        "created_at" => "",
                        "updated_at" => "",
                    ],
                    [
                        "id" => 5,
                        "name" => "youtube",
                        "link" => $restaurant->social_youtube ?? "",
                        "status" => 1,
                        "created_at" => "",
                        "updated_at" => "",
                    ],
                    [
                        "id" => 6,
                        "name" => "instagram",
                        "link" => $restaurant->social_instagram ?? "",
                        "status" => 1,
                        "created_at" => "",
                        "updated_at" => "",
                    ],
                    [
                        "id" => 7,
                        "name" => "whatsapp",
                        "link" => $restaurant->whatsapp ?? "",
                        "status" => 1,
                        "created_at" => "",
                        "updated_at" => "",
                    ],
                ],
                "play_store_config" => [
                    "status" => "false",
                    "link" => "",
                    "min_version" => "1",
                ],
                "app_store_config" => [
                    "status" => "false",
                    "link" => "",
                    "min_version" => "1",
                ],
                "software_version" => "1.0",
                "footer_text" => "copyright © Digitile",
                "decimal_point_settings" => 2,
                "schedule_order_slot_duration" => 0,
                "time_format" => "12",
                "promotion_campaign" => [],
                "social_login" => [
                    "google" => 0,
                    "facebook" => 0,
                ],
                "wallet_status" => 0,
                "loyalty_point_status" => 0,
                "ref_earning_status" => 0,
                "loyalty_point_item_purchase_point" => 0,
                "loyalty_point_exchange_rate" => 0,
                "loyalty_point_minimum_point" => 0,
                "digital_payment_status" => 1,
                "active_payment_method_list" => [],
                "whatsapp" => [
                    "status" => 1,
                    "number" => "",
                ],
                "cookies_management" => [
                    "status" => 0,
                    "text" => "Allow Cookies for this site",
                ],
                "toggle_dm_registration" => 0,
                "is_veg_non_veg_active" => 0,
                "otp_resend_time" => 60,
            ];

            return response()->json([
                'response' => $values,
                'message' => 'Config Found'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'response' => [],
                'message' => 'No data Found!'
            ], Response::HTTP_NOT_FOUND);
        }
    }



    public function getUserProfile(Request $request)
    {
        $user = auth()->user();
        if ($user->partner) {
            $vals = [
                "id" => $user->id,
                "f_name" => $user->partner->name,
                "l_name" => "",
                "phone" => $user->partner->mobile,
                "email" => $user->partner->email,
                "identity_number" => "",
                "identity_type" => "",
                "identity_image" => [],
                "image" => $user->partner->image_1920 ?: "",
                "password" => $user->password,
                "created_at" => $user->created_at,
                "updated_at" => $user->updated_at,
                "auth_token" => "",
                "fcm_token" => $user->user_token ?: "",
                "branch_id" => $user->company_id,
                "is_active" => 1,
                "application_status" => "",
                "login_hit_count" => 0,
                "is_temp_blocked" => 0,
                "temp_block_time" => "",
                "central_fcm_token" => 0
            ];


            return response()->json([
                'response' => [$vals],
                'message' => 'Profile found'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'response' => [],
                'message' => 'Profile Not found'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function getCurrentOrders(Request $request)
    {
        $user = auth()->user(); // Assuming you have an authenticated user

        $orderList = $this->getOrders($user);

        if (count($orderList) > 0) {
            return response()->json([
                'response' => $orderList,
                'message' => 'Sale Orders found'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'response' => [],
                'message' => 'Data Not found'
            ], Response::HTTP_NOT_FOUND);
        }
    }
    public function getAllOrders(Request $request)
    {
        $user = auth()->user(); // Assuming you have an authenticated user

        $orderList = $this->getOrders($user);

        if (count($orderList) > 0) {
            return response()->json([
                'response' => $orderList,
                'message' => 'Sale Orders found'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'response' => [],
                'message' => 'Data Not found'
            ], Response::HTTP_NOT_FOUND);
        }
    }
    public function getOrders($user)
    {
        $orderList = [];

        if ($user) {
            $driver = $user->partner;
            if ($driver) {
                $saleOrders = SaleOrder::where('driver_id', $driver->id)->get();

                // dd($saleOrders);
                foreach ($saleOrders as $saleOrder) {
                    $order_status = "";
                    switch ($saleOrder->order_status) {
                        case "2":
                            $order_status = "Draft";
                            break;
                        case "3":
                            $order_status = "Confirmed";
                            break;
                        case "4":
                            $order_status = "In Progress";
                            break;
                        case "5":
                            $order_status = "Ready";
                            break;
                        case "6":
                            $order_status = "Out For Delivery";
                            break;
                        case "7":
                            $order_status = "Delivered";
                            break;
                    }

                    if ($saleOrder->sale_order_type == "1") {
                        $deliveryProduct = ProductProduct::where('is_delivery', true)->where('active', 1)->first();
                        $delivery_charge = 0;
                        foreach ($saleOrder->orderLines as $line) {
                            if ($deliveryProduct->id == $line->product_id) {
                                $delivery_charge = $line->price_total;
                            }
                        }
                    } else {
                        $delivery_charge = 0;
                    }

                    $address = $saleOrder->partnerShipping->city . ' , ' . $saleOrder->partnerShipping->street;
                    if ($saleOrder->partnerShipping->street2) {
                        $address .= ' , ' . $saleOrder->partnerShipping->street2;
                    }

                    $orders = SaleOrder::where('partner_id', $saleOrder->partner->id)->get();
                    $orders_count = count($orders);
                    // dd($saleOrder->user->id);
                    // dd($saleOrder->driverOrder);


                    $values = [
                        "id" => $saleOrder->id,
                        "name" => $saleOrder->name,
                        "user_id" => $saleOrder->user_id ? $saleOrder->user->id : null,
                        "order_amount" => $saleOrder->amount_total,
                        "coupon_discount_amount" => 0,
                        "coupon_discount_title" => "",
                        "payment_status" => "unpaid",
                        "order_status" => $order_status,
                        "order_time_to_be_ready" => $saleOrder->order_time_to_be_ready,
                        "assign_time_time" => $saleOrder->assign_time_time,
                        "total_tax_amount" => $saleOrder->amount_tax,
                        "payment_method" => "cash_on_delivery",
                        "transaction_reference" => "",
                        "delivery_address_id" => $saleOrder->partnerShipping->id,
                        "created_at" => $saleOrder->created_at,
                        "updated_at" => $saleOrder->updated_at,
                        "checked" => 1,
                        "delivery_man_id" => $saleOrder->driver_id,
                        "delivery_charge" => $delivery_charge,
                        "order_note" => "",
                        "coupon_code" => "",
                        "order_type" => $saleOrder->sale_order_type == "1" ? "delivery" : "Pick Up",
                        "branch_id" => 1,
                        "callback" => "",
                        // "delivery_date" => $saleOrder->delivery_date->format('Y-m-d'),
                        // "delivery_time" => $saleOrder->delivery_date->format('H:i:s'),
                        "delivery_date" => Carbon::parse($saleOrder->delivery_date)->format('Y-m-d'),
                        "delivery_time" => Carbon::parse($saleOrder->delivery_date)->format('H:i:s'),
                        "extra_discount" => "0.00",
                        "delivery_address" => [
                            "id" => $saleOrder->partnerShipping->id,
                            "address_type" => $saleOrder->partnerShipping->type,
                            "contact_person_number" => $saleOrder->partnerShipping->mobile ? $saleOrder->partnerShipping->mobile : $saleOrder->partnerShipping->phone,
                            "floor" => "",
                            "house" => "",
                            "road" => $saleOrder->partnerShipping->street,
                            "address" => $address,
                            "latitude" => $saleOrder->partnerShipping->partner_latitude,
                            "longitude" => $saleOrder->partnerShipping->partner_longitude,
                            "created_at" => $saleOrder->partnerShipping->created_at,
                            "updated_at" => $saleOrder->partnerShipping->updated_at,
                            "user_id" => $saleOrder->partnerShipping->user_id ? $saleOrder->partnerShipping->user_id : 0,
                            "contact_person_name" => $saleOrder->partnerShipping->name
                        ],
                        "preparation_time" => 0,
                        "table_id" => "",
                        "number_of_people" => "",
                        "table_order_id" => "",
                        "customer" => [
                            "id" => $saleOrder->partner->id,
                            "f_name" => $saleOrder->partner->name,
                            "l_name" => "",
                            "email" => $saleOrder->partner->email ? $saleOrder->partner->email : "",
                            "user_type" => "",
                            "is_active" => 1,
                            "image" => "null",
                            "is_phone_verified" => 0,
                            "email_verified_at" => "",
                            "created_at" => $saleOrder->partner->created_at,
                            "updated_at" => $saleOrder->partner->updated_at,
                            "email_verification_token" => "",
                            "phone" => $saleOrder->partner->mobile ? $saleOrder->partner->mobile : $saleOrder->partner->phone,
                            "cm_firebase_token" => "",
                            "point" => 0,
                            "temporary_token" => "",
                            "login_medium" => "",
                            "wallet_balance" => "0.000",
                            "refer_code" => "null",
                            "refer_by" => "null",
                            "login_hit_count" => 0,
                            "is_temp_blocked" => 0,
                            "temp_block_time" => "null",
                            "orders_count" => $orders_count
                        ]
                    ];

                    $orderList[] = $values;
                }
            }
        }

        return $orderList;
    }

    public function getOrderDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        } else {
            $req = $request->all();

            $order_id = $req['order_id'];
            $productInfo = new CustomHelper();
            $orderList = [];

            $saleOrder = SaleOrder::where('id', $order_id)->first();
            // dd( $saleOrder->saleOrderLines);
            if ($saleOrder) {
                foreach ($saleOrder->saleOrderLines as $line) {
                    $desc = strip_tags($line->product_id->description ?? '');

                    // $taxes = $line->product_id->taxes_id;
                    // $amount = 0;
                    // foreach ($taxes as $tax) {
                    //     $amount = $tax->amount;
                    // }
                    $amount = ResCompany::first()->tax;
                    // dd($line);
                    $values = [
                        "id" => $line->id,
                        "product_id" => $line->product->id,
                        "order_id" => $order_id,
                        "price" => $line->price_total,
                        "product_details" => [
                            "id" => $line->product->id,
                            "name" => $line->product->name,
                            "description" => $desc,
                            "image" => $line->product->image_attachment
                                ? "/web/content/" . $line->product->image_attachment->id
                                : "",
                            // "price" => $productInfo->getProductProductPrice($line->product),
                            "price" => round($line->price_total / $line->product_uom_qty, 2),
                            "variations" => [],
                            "add_ons" => [],
                            "extra_products" => "[]",
                            "required_ingredients" => [],
                            "removable_ingredients" => [],
                            "tax" => $amount,
                            "available_time_starts" => "",
                            "available_time_ends" => "",
                            "status" => 1,

                            "created_at" => $line->product->created_at,
                            "updated_at" => $line->product->updated_at,

                            "attributes" => [],
                            "category_ids" => [],
                            "choice_options" => [],
                            "discount" => 0,
                            "discount_type" => "percent",
                            "tax_type" => "percent",
                            "set_menu" => 0,
                            "popularity_count" => 1,
                            "product_type" => "",
                            "slug" => "",
                            "removableIngredient_details" => [],
                            "translations" => [],
                        ],



                        "created_at" => $line->product->created_at,
                        "updated_at" => $line->product->updated_at,


                        "attributes" => [],
                        "category_ids" => [],
                        "choice_options" => [],
                        "discount" => 0,
                        "discount_type" => "percent",
                        "tax_type" => "percent",
                        "set_menu" => 0,
                        "popularity_count" => 1,
                        "product_type" => "",
                        "slug" => "",
                        "removableIngredient_details" => [],
                        "translations" => [],
                    ];

                    $orderList[] = $values;
                }

                return response()->json([
                    'response' => $orderList,
                    'message' => 'Sale Order Details Found'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'No data Found!'
                ], Response::HTTP_NOT_FOUND);
            }
        }
    }

    public function confirmDelivery(Request $request)
    {
        $requestData = $request->all();
        if (isset($requestData['sale_order_id'])) {
            $saleOrderId = $requestData['sale_order_id'];
        } else {
            return response()->json([
                'message' => 'Sale Order Id Not Sent!'
            ], Response::HTTP_NOT_FOUND);
        }

        if ($saleOrderId) {
            $saleOrder = SaleOrder::where('id', $saleOrderId)->first();

            if ($saleOrder) {
                if ($saleOrder->order_status == 7) {
                    return response()->json([
                        'message' => 'Order Already Delivered!'
                    ], Response::HTTP_BAD_REQUEST);
                } else {
                    $saleOrder->update(['order_status' => '7']);
                    return response()->json([
                        'message' => 'Sale Order Delivered!'
                    ], Response::HTTP_OK);
                }
            } else {
                return response()->json([
                    'message' => 'Sale Order Not Found!'
                ], Response::HTTP_NOT_FOUND);
            }
        }
    }
    public function updatePaymentStatus(Request $request)
    {
        $requestData = $request->all();
        if (isset($requestData['order_id']) && isset($requestData['status'])) {
            $saleOrderId = $requestData['order_id'];
            $paymentStatus = $requestData['status'];
            if ($saleOrderId) {
                $saleOrder = SaleOrder::find($saleOrderId);

                if ($saleOrder) {
                    $saleOrder->payment_status = $paymentStatus;
                    $saleOrder->save();


                    return response()->json([
                        'status' => 200,
                        'message' => 'Sale Order Payment Status Changed!'
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Sale Order Not Found!'
                    ], Response::HTTP_NOT_FOUND);
                }
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Sale Order Not Found!'
                ], Response::HTTP_NOT_FOUND);
            }
        } else {
            return response()->json([
                'message' => 'Sale Order or Status Not Defined!'
            ], Response::HTTP_NOT_FOUND);
        }
    }


    public function updateOrderStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        }

        $requestData = $request->all();
        $saleOrderId = $requestData['order_id'];
        $orderStatus = $requestData['status'];

        if ($saleOrderId) {
            $saleOrder = SaleOrder::where('id', $saleOrderId)->first();

            if ($saleOrder) {
                if ($orderStatus == 'delivered') {
                    $orderStatusId = '7';
                } else {
                    if ($orderStatus == 'out_for_delivery') {
                        $orderStatusId = '6';
                    }
                }
                $saleOrder->update(['order_status' => $orderStatusId]);

                if ($orderStatusId == '7') {
                    return response()->json([

                        'message' => 'Sale Order Delivered!'
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'message' => 'Sale Order Out For Delivery!'
                    ], Response::HTTP_NOT_FOUND);
                }
            } else {
                return response()->json([
                    'message' => 'Sale Order Not Found!'
                ], Response::HTTP_NOT_FOUND);
            }
        }
    }


    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        if ($user->partner) {
            $requestData = $request->all();

            if (isset($requestData['name'])) {
                $user->partner->name = $requestData['name'];
            }

            if (isset($requestData['phone'])) {
                $user->partner->phone = $requestData['phone'];
            }

            if (isset($requestData['image'])) {
                $user->partner->image_1920 = $requestData['image'];
            }

            $user->partner->save();

            return response()->json([
                'status' => 200,
                'message' => 'Profile Updated!'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Profile Found!'
            ], Response::HTTP_NOT_FOUND);
        }
    }
    public function getDeliveryManConfig(Request $request)
    {
        $detail = new CustomHelper();

        try {
            $requestData = $request->all();
            $companyId = $requestData['company_id'] ?? null;

            if ($companyId) {
                $restaurantName = ResCompany::where('id', $companyId)->first();
            } else {
                $restaurantName = ResCompany::where('parent_id', null)
                    ->orderBy('id')
                    ->first();
            }
        } catch (\Exception $e) {
            $restaurantName = ResCompany::where('parent_id', null)
                ->orderBy('id')
                ->first();
        }

        if ($restaurantName) {
            // $tax = AccountTax::first();
            // $amountTax = $tax ? $tax->amount : 0.0;

            $calendar = ResourceCalendar::where('company_id', $restaurantName->id)
                ->where('active', true)
                ->first();

            $restaurantScheduleTime = [];

            if ($calendar) {
                $calendarAttendance = ResourceCalendarAttendance::where('calendar_id', $calendar->id)
                    ->orderBy('dayofweek')
                    ->orderBy('hour_from')
                    ->get();

                foreach ($calendarAttendance as $att) {
                    $dayName = [
                        0 => 'Monday',
                        1 => 'Tuesday',
                        2 => 'Wednesday',
                        3 => 'Thursday',
                        4 => 'Friday',
                        5 => 'Saturday',
                        6 => 'Sunday',
                    ];
                    $hour_from_parset = Carbon::createFromFormat('H:i:s', $att->hour_from)->format('G') + Carbon::createFromFormat('H:i:s', $att->hour_from)->format('i') / 60;
                    $hour_to_parset = Carbon::createFromFormat('H:i:s', $att->hour_to)->format('G') + Carbon::createFromFormat('H:i:s', $att->hour_to)->format('i') / 60;

                    $valuesAtt = [
                        "day_name" => $dayName[$att->dayofweek],
                        "day" => $att->dayofweek <= 6 ? (int)$att->dayofweek : 0,
                        "opening_time" => $detail->formatTimeFromFloat($hour_from_parset),
                        "closing_time" => $detail->formatTimeFromFloat($hour_to_parset),
                    ];

                    $restaurantScheduleTime[] = $valuesAtt;
                }
            }

            $values = [
                "company_id" => $restaurantName->id,
                "restaurant_name" => $restaurantName->name,
                "restaurant_open_time" => "",
                "restaurant_close_time" => "",
                "restaurant_address" => $restaurantName->street,
                "restaurant_phone" => $restaurantName->phone,
                "restaurant_email" => $restaurantName->email,
                "currency_symbol" => $restaurantName->resCurrency->name,
                "currency_symbol_en" => $restaurantName->resCurrency->name,
                "currency_id" => $restaurantName->resCurrency->id,
                "restaurant_logo" => "/web/content/" . ($restaurantName->logo_web_attachment ? $restaurantName->logo_web_attachment->id : ""),
                "restaurant_logo_dark" => "",
                "restaurant_schedule_time" => $restaurantScheduleTime,
                "restaurant_location_coverage" => $detail->getCompanyZonesWithoutId(),
                "minimum_order_value" => 1,
                "base_urls" => [],
                // "tax_percent" => $amountTax,
                "delivery_charge" => 0,
                "delivery_management" => [
                    "status" => 1,
                    "min_shipping_charge" => 0,
                    "shipping_per_km" => 0,
                ],
                "cash_on_delivery" => "true",
                "digital_payment" => "true",
                "branches" => $this->getBranchesInformation(),
                "terms_and_conditions" => $restaurantName->terms_and_conditions ?? "",
                "privacy_policy" => $restaurantName->privacy_policy ?? "",
                "support" => $restaurantName->support ?? "",
                "email_verification" => "",
                "phone_verification" => "",
                "currency_symbol_position" => $restaurantName->resCurrency->position == "before" ? "left" : "right",
                "maintenance_mode" => false,
                "country" => "",
                "self_pickup" => "true",
                "delivery" => "true",
                "social_media_link" => [
                    [
                        "id" => 1,
                        "name" => "twitter",
                        "link" => $restaurantName->social_twitter ?? "",
                        "status" => 1,
                        "created_at" => "",
                        "updated_at" => "",
                    ],
                    [
                        "id" => 2,
                        "name" => "facebook",
                        "link" => $restaurantName->social_facebook ?? "",
                        "status" => 1,
                        "created_at" => "",
                        "updated_at" => "",
                    ],
                    [
                        "id" => 3,
                        "name" => "gitHub",
                        "link" => $restaurantName->social_github ?? "",
                        "status" => 1,
                        "created_at" => "",
                        "updated_at" => "",
                    ],
                    [
                        "id" => 4,
                        "name" => "linkedIn",
                        "link" => $restaurantName->social_linkedin ?? "",
                        "status" => 1,
                        "created_at" => "",
                        "updated_at" => "",
                    ],
                    [
                        "id" => 5,
                        "name" => "youtube",
                        "link" => $restaurantName->social_youtube ?? "",
                        "status" => 1,
                        "created_at" => "",
                        "updated_at" => "",
                    ],
                    [
                        "id" => 6,
                        "name" => "instagram",
                        "link" => $restaurantName->social_instagram ?? "",
                        "status" => 1,
                        "created_at" => "",
                        "updated_at" => "",
                    ],
                    [
                        "id" => 7,
                        "name" => "whatsapp",
                        "link" => $restaurantName->whatsapp ?? "",
                        "status" => 1,
                        "created_at" => "",
                        "updated_at" => "",
                    ],
                ],
                "play_store_config" => [
                    "status" => "false",
                    "link" => "",
                    "min_version" => "1",
                ],
                "app_store_config" => [
                    "status" => "false",
                    "link" => "",
                    "min_version" => "1",
                ],
                "software_version" => "1.0",
                "footer_text" => "copyright © Digitile",
                "decimal_point_settings" => 2,
                "schedule_order_slot_duration" => 0,
                "time_format" => "12",
                "promotion_campaign" => [],
                "social_login" => [
                    "google" => 0,
                    "facebook" => 0,
                ],
                "wallet_status" => 0,
                "loyalty_point_status" => 0,
                "ref_earning_status" => 0,
                "loyalty_point_item_purchase_point" => 0,
                "loyalty_point_exchange_rate" => 0,
                "loyalty_point_minimum_point" => 0,
                "digital_payment_status" => 1,
                "active_payment_method_list" => [],
                "whatsapp" => [
                    "status" => 1,
                    "number" => "",
                ],
                "cookies_management" => [
                    "status" => 0,
                    "text" => "Allow Cookies for this site",
                ],
                "toggle_dm_registration" => 0,
                "is_veg_non_veg_active" => 0,
                "otp_resend_time" => 60,
            ];

            return response()->json([
                'response' => [$values],
                'message' => 'Config Found'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'response' => [],
                'message' => 'Config Not Found ! '
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
