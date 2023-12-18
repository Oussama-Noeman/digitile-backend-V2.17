<?php

namespace App\Http\Controllers\Api\V1\Platform;

use App\Http\Controllers\Controller;

use App\Models\Tenant\ResPartner;
use App\Utils\CustomHelper;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\ProductInfo;
use App\Models\Tenant\ProductProduct;
use App\Models\Tenant\ResCompany;
use App\Models\Tenant\ResourceCalendar;
use App\Models\Tenant\ResourceCalendarAttendance;
use App\Models\Tenant\SaleOrder;

use App\Models\Tenant\User;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class MainController extends Controller
{
    public function getBranches()
    {
        $branches = ResCompany::all();
        $branchList = [];

        if ($branches->isNotEmpty()) {
            foreach ($branches as $branch) {
                $values = [
                    "id" => $branch->id,
                    "name" => $branch->name,
                ];
                $branchList[] = $values;
            }
            return response()->json([
                'response' => $branchList,
                'message' => 'Branches Found'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'response' => [],
                'message' => 'No branch Found ! '
            ], Response::HTTP_NOT_FOUND);
        }
    }
    public function getConfig(Request $request)
    {
        $detail = new CustomHelper();

        try {
            $req = $request->all();
            $company_id = $req['company_id'] ?? null;

            if ($company_id) {
                $restaurant_name = ResCompany::where('id', $company_id)->first();
            } else {
                $restaurant_name = ResCompany::where('parent_id', null)
                    ->orderBy('id')
                    ->first();
            }
        } catch (\Exception $e) {
            $restaurant_name = ResCompany::where('parent_id', null)
                ->orderBy('id')
                ->first();
        }

        if ($restaurant_name) {
            // $tax = \DB::table('account_tax')->first();
            // $amount_tax = $tax ? $tax->amount : 0.0;

            $calendar = ResourceCalendar::where('company_id', $restaurant_name->id)
                ->where('active', true)
                ->first();

            $restaurant_schedule_time = [];

            if ($calendar) {
                $calendar_attendance = ResourceCalendarAttendance::where('calendar_id', $calendar->id)
                    ->orderBy('dayofweek')
                    ->orderBy('hour_from')
                    ->get();

                foreach ($calendar_attendance as $att) {
                    $dayName = [
                        0 => 'Monday',
                        1 => 'Tuesday',
                        2 => 'Wednesday',
                        3 => 'Thursday',
                        4 => 'Friday',
                        5 => 'Saturday',
                        6 => 'Sunday',
                    ];

                    $values_att = [
                        "day_name" => $dayName[$att->dayofweek],
                        "day" => ($att->dayofweek <= 6) ? (int) $att->dayofweek : 0,
                        "opening_time" => $detail->formatTimeFromFloat($att->hour_from),
                        "closing_time" => $detail->formatTimeFromFloat($att->hour_to),
                    ];

                    $restaurant_schedule_time[] = $values_att;
                }
            }

            $values = [
                "company_id" => $restaurant_name->id,
                "restaurant_name" => $restaurant_name->name,
                "restaurant_open_time" => "",
                "restaurant_close_time" => "",
                "restaurant_address" => $restaurant_name->street,
                "restaurant_phone" => $restaurant_name->phone,
                "restaurant_email" => $restaurant_name->email,
                "currency_symbol" => $restaurant_name->resCurrency->name,
                "currency_symbol_en" => $restaurant_name->resCurrency->name,
                "currency_id" => $restaurant_name->currency_id,
                "restaurant_logo" => "/storage/" . ($restaurant_name->logo_web_attachment ? $restaurant_name->logo_web_attachment : ""),
                "restaurant_logo_dark" => "",
                "restaurant_schedule_time" => $restaurant_schedule_time,
                "restaurant_location_coverage" => $detail->getCompanyZonesWithoutId(),
                "minimum_order_value" => 1,
                "base_urls" => [],
                // "tax_percent" => $amount_tax,
                "delivery_charge" => 0,
                "delivery_management" => [
                    "status" => 1,
                    "min_shipping_charge" => 0,
                    "shipping_per_km" => 0,
                ],
                "cash_on_delivery" => "true",
                "digital_payment" => "true",
                "branches" => $this->getBranchesInformation(),
                "terms_and_conditions" => $restaurant_name->terms_and_conditions ?? "",
                "privacy_policy" => $restaurant_name->privacy_policy ?? "",
                "support" => $restaurant_name->support ?? "",
                "email_verification" => "",
                "phone_verification" => "",
                "currency_symbol_position" => $restaurant_name->resCurrency->position == "before" ? "left" : "right",
                "maintenance_mode" => false,
                "country" => "",
                "self_pickup" => "true",
                "delivery" => "true",
                "social_media_link" => [
                    [
                        "id" => 1,
                        "name" => "twitter",
                        "link" => $restaurant_name->social_twitter ?? "",
                        "status" => 1,
                        "created_at" => "",
                        "updated_at" => "",
                    ],
                    [
                        "id" => 2,
                        "name" => "facebook",
                        "link" => $restaurant_name->social_facebook ?? "",
                        "status" => 1,
                        "created_at" => "",
                        "updated_at" => "",
                    ],
                    [
                        "id" => 3,
                        "name" => "gitHub",
                        "link" => $restaurant_name->social_github ?? "",
                        "status" => 1,
                        "created_at" => "",
                        "updated_at" => "",
                    ],
                    [
                        "id" => 4,
                        "name" => "linkedIn",
                        "link" => $restaurant_name->social_linkedin ?? "",
                        "status" => 1,
                        "created_at" => "",
                        "updated_at" => "",
                    ],
                    [
                        "id" => 5,
                        "name" => "youtube",
                        "link" => $restaurant_name->social_youtube ?? "",
                        "status" => 1,
                        "created_at" => "",
                        "updated_at" => "",
                    ],
                    [
                        "id" => 6,
                        "name" => "instagram",
                        "link" => $restaurant_name->social_instagram ?? "",
                        "status" => 1,
                        "created_at" => "",
                        "updated_at" => "",
                    ],
                    [
                        "id" => 7,
                        "name" => "whatsapp",
                        "link" => $restaurant_name->whatsapp ?? "",
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
                "theme" => [
                    "colors" => [
                        "main_color" => "#E97424",
                        "main_dark_color" => "#E97424",
                        "secondary_color" => "#E97424",
                        "secondary_dark_color" => "#E97424",
                    ],
                ],
                "categories_per_slide" => 7,
                "default_language" => "en",
                "business_type" => 1,
                "tax_enabled" => 1,
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
    public function getOrders($company_id, $order_status = null, $order_status_is_or_not = null)
    {
        $orderList = [];

        $orderStatusId = null;

        if ($order_status == "Draft") {
            $orderStatusId = "2";
        } elseif ($order_status == "Confirmed") {
            $orderStatusId = "3";
        } elseif ($order_status == "In Progress") {
            $orderStatusId = "4";
        } elseif ($order_status == "Ready") {
            $orderStatusId = "5";
        } elseif ($order_status == "Out For Delivery") {
            $orderStatusId = "6";
        } elseif ($order_status == "Delivered") {
            $orderStatusId = "7";
        }

        $detail = new CustomHelper();

        if ($company_id) {
            $query = SaleOrder::where('company_id', $company_id);

            if ($order_status) {
                if ($order_status_is_or_not) {
                    $query->where('order_status', $orderStatusId);
                } else {
                    $query->where('order_status', '!=', $orderStatusId);
                }
            }

            $saleOrders = $query
                ->where('sale_order_type_id', '!=', "3")
                ->where('state', '!=', 'cancel')
                ->orderBy('created_at', 'desc')
                ->get();

            if ($saleOrders) {
                foreach ($saleOrders as $saleOrder) {
                    $deliveryCharge = 0;

                    if ($saleOrder->sale_order_type_id == "1") {
                        $deliveryProduct = ProductProduct::where('is_delivery', true)
                            ->first();

                        if ($deliveryProduct) {
                            foreach ($saleOrder->saleOrderLines as $line) {
                                if ($deliveryProduct->id == $line->product_id) {
                                    $deliveryCharge = $line->price_total;
                                }
                            }
                        }
                    }

                    $address = $saleOrder->partnerShipping->city;
                    if ($saleOrder->partnerShipping->street) {
                        $address .= ' , ' . $saleOrder->partnerShipping->street;
                    }
                    if ($saleOrder->partnerShipping->street2) {
                        $address .= ' , ' . $saleOrder->partnerShipping->street2;
                    }

                    $orders = SaleOrder::where('partner_id', $saleOrder->partner->id)
                        ->where('company_id', $company_id)
                        ->where('sale_order_type_id', '!=', "3")
                        ->get();

                    $ordersCount = $orders ? count($orders) : 0;
                    // dd($saleOrder);
                    $orderStatusId = [
                        "2" => "Draft",
                        "3" => "Confirmed",
                        "4" => "In Progress",
                        "5" => "Ready",
                        "6" => "Out For Delivery",
                        "7" => "Delivered",
                    ][$saleOrder->order_status];

                    $preparationTime = $detail->getPreparationTime($saleOrder);
                    $preparationTimeStr = $preparationTime == 0 ? "0" : strval($preparationTime);

                    $values = [
                        "id" => $saleOrder->id,
                        "name" => $saleOrder->name,
                        "user_id" => $saleOrder->user_id ? $saleOrder->user->id : 0,
                        "order_amount" => $saleOrder->amount_total,
                        "coupon_discount_amount" => 0,
                        "coupon_discount_title" => "",
                        "payment_status" => "unpaid",
                        "order_status" => $orderStatusId,
                        "order_time_to_be_ready" => $saleOrder->order_time_to_be_ready,
                        "assign_time_time" => $saleOrder->assign_time_time,
                        "total_tax_amount" => $saleOrder->amount_tax,
                        "payment_method" => "cash_on_delivery",
                        "transaction_reference" => "",
                        "delivery_address_id" => $saleOrder->partnerShipping->id,
                        "created_at" => $saleOrder->created_at,
                        "updated_at" => $saleOrder->updated_at,
                        "checked" => 1,
                        "delivery_man_id" => $saleOrder->driver_id ? $saleOrder->driver->id : 0,
                        "delivery_charge" => $deliveryCharge,
                        "order_note" => "",
                        "coupon_code" => "",
                        "order_type" => $saleOrder->sale_order_type_id == "1" ? "delivery" : "Pick Up",
                        "branch_id" => 1,
                        "callback" => "",

                        "delivery_date" => $saleOrder->delivery_date ? $saleOrder->delivery_date : null,
                        // ->format('Y-m-d') : null,
                        "delivery_time" => $saleOrder->delivery_date ? $saleOrder->delivery_date : null,

                        // ->format('H:i:s') : null,
                        "extra_discount" => "0.00",
                        "delivery_address" => [
                            "id" => $saleOrder->partnerShipping->id,
                            "address_type" => $saleOrder->partnerShipping->type,
                            "contact_person_number" => $saleOrder->partnerShipping->mobile ?: $saleOrder->partnerShipping->phone ?: "",
                            "floor" => "",
                            "house" => "",
                            "road" => $saleOrder->partnerShipping->street,
                            "address" => $address,
                            "latitude" => $saleOrder->partnerShipping->partner_latitude,
                            "longitude" => $saleOrder->partnerShipping->partner_longitude,
                            "created_at" => $saleOrder->partnerShipping->created_at,
                            "updated_at" => $saleOrder->partnerShipping->updated_at,
                            "user_id" => $saleOrder->partnerShipping->user_id ? $saleOrder->partnerShipping->user_id->id : 0,
                            "contact_person_name" => $saleOrder->partnerShipping->name,
                        ],
                        "preparation_time" => $preparationTimeStr,
                        "table_id" => "",
                        "number_of_people" => "",
                        "table_order_id" => "",
                        "customer" => [
                            "id" => $saleOrder->partner->id,
                            "f_name" => $saleOrder->partner->name,
                            "l_name" => "",
                            "email" => $saleOrder->partner->email ?: "",
                            "user_type" => "",
                            "is_active" => 1,
                            "image" => $saleOrder->partner->team_member_image_attachment ? "/storage/" . $saleOrder->partner->team_member_image_attachment : "",
                            "is_phone_verified" => 0,
                            "email_verified_at" => "",
                            "created_at" => $saleOrder->partner->create_date,
                            "updated_at" => $saleOrder->partner->write_date,

                            "email_verification_token" => "",
                            "phone" => $saleOrder->partner->mobile ?: $saleOrder->partner->phone ?: "",
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
                            "orders_count" => $ordersCount,
                        ],
                    ];

                    $orderList[] = $values;
                }
            }
        }

        // $returnValues = [
        //     "list" => $orderList,
        //     "total_size" => count($saleOrders) ?: 0,
        // ];

        return $orderList;
    }

    public function getAllOrders()
    {
        $user = auth()->user();
        $orderList = [];

        if ($user) {
            $orderList = $this->getOrders($user->company_id);

            if (count($orderList)) {

                return response()->json([
                    'response' => $orderList['list'],
                    'message' => 'Sale Orders Found'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'No Data Found!'
                ], Response::HTTP_NOT_FOUND);
            }
        } else {
            return response()->json([
                'response' => [],
                'message' => 'User Not Found!'
            ], Response::HTTP_NOT_FOUND);
        }
    }
    public function getBranchesInformation()
    {
        $branches = new CustomHelper();
        $branchList = [];

        foreach ($branches as $branch) {
            $address = "";
            if ($branch->city) {
                $address = $branch->city;
            }
            if ($branch->street) {
                $address .= ($address ? ' , ' : '') . $branch->street;
            }
            if ($branch->street2) {
                $address .= ($address ? ' , ' : '') . $branch->street2;
            }

            $values = [
                "id" => $branch->id,
                "name" => $branch->name,
                "email" => $branch->email,
                "longitude" => $branch->partner->partner_longitude,
                // Assuming the relationship is defined
                "latitude" => $branch->partner->partner_latitude,
                // Assuming the relationship is defined
                "address" => $address,
                "coverage" => 0,
                // You can set a proper value
                "zones" => $this->getCompanyZonesWithoutId($branch->id),
                // Define this method
            ];

            $branchList[] = $values;
        }

        return $branchList;
    }

    public function getCurrentOrders(Request $request)
    {
        // $user = User::where('id', $request->user()->id)->first();
        $user = auth()->user();

        if ($user) {
            $orderList = $this->getOrders($user->company_id, "Delivered", false);

            if (count($orderList)) {
                return response()->json([
                    'response' => $orderList,
                    'message' => 'Sale Orders Found'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'No data Found!'
                ], Response::HTTP_NOT_FOUND);
            }
        } else {
            return response()->json([
                'response' => [],
                'message' => 'User Not Found!'
            ], Response::HTTP_NOT_FOUND);
        }
    }
    public function getCompletedOrdersList($companyId, $limit, $offset)
    {
        $orderList = [];

        $orderStatusId = "7";

        $detail = new CustomHelper();
        if ($companyId) {
            $saleOrdersDelivered = SaleOrder::where([
                ['company_id', '=', $companyId],
                ['sale_order_type_id', '!=', '3'],
                ['order_status', '=', $orderStatusId]
            ])->get();

            $saleOrdersCancel = SaleOrder::where([
                ['company_id', '=', $companyId],
                ['sale_order_type_id', '!=', '3'],
                ['state', '=', 'cancel']
            ])->get();

            $saleOrders = SaleOrder::where('company_id', $companyId)
                ->where(function ($query) use ($saleOrdersDelivered, $saleOrdersCancel) {
                    $query->whereIn('id', $saleOrdersDelivered->pluck('id'))
                        ->orWhereIn('id', $saleOrdersCancel->pluck('id'));
                })
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->offset($offset)
                ->get();

            if ($saleOrders->isNotEmpty()) {
                foreach ($saleOrders as $saleOrder) {
                    $orderStatusId = [
                        "2" => "Draft",
                        "3" => "Confirmed",
                        "4" => "In Progress",
                        "5" => "Ready",
                        "6" => "Out For Delivery",
                        "7" => "Delivered"
                    ][$saleOrder->order_status] ?? "Unknown";

                    if ($saleOrder->state == "cancel") {
                        $orderStatusId = "Canceled";
                    }

                    $deliveryCharge = 0;
                    if ($saleOrder->sale_order_type_id == "1") {
                        $deliveryProduct = ProductProduct::where('is_delivery', true)->first();
                        if ($deliveryProduct) {
                            $deliveryCharge = $saleOrder->order_line()
                                ->where('product_id', $deliveryProduct->id)
                                ->sum('price_total');
                        }
                    }

                    $address = "";
                    if ($saleOrder->partnerShipping->city) {
                        $address = $saleOrder->partnerShipping->city;
                    }
                    if ($saleOrder->partnerShipping->street) {
                        $address .= $address ? ' , ' : '';
                        $address .= $saleOrder->partnerShipping->street;
                    }
                    if ($saleOrder->partnerShipping->street2) {
                        $address .= $address ? ' , ' : '';
                        $address .= $saleOrder->partnerShipping->street2;
                    }

                    $orders = SaleOrder::where('partner_id', $saleOrder->partner->id)
                        ->where('company_id', $companyId)
                        ->where('sale_order_type_id', '!=', "3")
                        ->get();

                    $ordersCount = $orders->count();

                    // $preparationTime = $detail->getPreparationTime($saleOrder);
                    // $preparationTimeStr = $preparationTime == 0 ? "0" : strval($preparationTime);

                    $values = [
                        "id" => $saleOrder->id,
                        "name" => $saleOrder->name,
                        "user_id" => $saleOrder->user ? $saleOrder->user->id : 0,
                        "order_amount" => $saleOrder->amount_total,
                        "coupon_discount_amount" => 0,
                        "coupon_discount_title" => "",
                        "payment_status" => "unpaid",
                        "order_status" => $orderStatusId,
                        "order_time_to_be_ready" => $saleOrder->order_time_to_be_ready ?: null,
                        "assign_time_time" => $saleOrder->assign_time_time ?: null,
                        "total_tax_amount" => $saleOrder->amount_tax,
                        "payment_method" => "cash_on_delivery",
                        "transaction_reference" => "",
                        "delivery_address_id" => $saleOrder->partnerShipping->id,
                        "created_at" => $saleOrder->created_at,
                        "updated_at" => $saleOrder->write_date,
                        "checked" => 1,
                        "delivery_man_id" => $saleOrder->driver_id ? $saleOrder->driver_id->id : 0,
                        "delivery_charge" => $deliveryCharge,
                        "order_note" => "",
                        "coupon_code" => "",
                        "order_type" => $saleOrder->sale_order_type_id == "1" ? "delivery" : "Pick Up",
                        "branch_id" => 1,
                        "callback" => "",
                        "delivery_date" => $saleOrder->delivery_date ? $saleOrder->delivery_date : null,
                        "delivery_time" => $saleOrder->delivery_date ? $saleOrder->delivery_date : null,
                        // "delivery_date" => $saleOrder->delivery_date ? $saleOrder->delivery_date->toDateString() : null,
                        // "delivery_time" => $saleOrder->delivery_date ? $saleOrder->delivery_date->toTimeString() : null,
                        "extra_discount" => "0.00",
                        "delivery_address" => [
                            "id" => $saleOrder->partnerShipping->id,
                            "address_type" => $saleOrder->partnerShipping->type,
                            "contact_person_number" => $saleOrder->partnerShipping->mobile ?: $saleOrder->partnerShipping->phone ?: "",
                            "floor" => "",
                            "house" => "",
                            "road" => $saleOrder->partnerShipping->street,
                            "address" => $address,
                            "latitude" => $saleOrder->partnerShipping->partner_latitude,
                            "longitude" => $saleOrder->partnerShipping->partner_longitude,
                            "created_at" => $saleOrder->partnerShipping->created_at,
                            "updated_at" => $saleOrder->partnerShipping->write_date,
                            "user_id" => $saleOrder->partnerShipping->user ? $saleOrder->partnerShipping->user->id : 0,
                            "contact_person_name" => $saleOrder->partnerShipping->name
                        ],
                        // "preparation_time" => $preparationTimeStr,
                        "table_id" => "",
                        "number_of_people" => "",
                        "table_order_id" => "",
                        "customer" => [
                            "id" => $saleOrder->partner->id,
                            "f_name" => $saleOrder->partner->name,
                            "l_name" => "",
                            "email" => $saleOrder->partner->email ?: "",
                            "user_type" => "",
                            "is_active" => 1,
                            "image" => "/storage/" . ($saleOrder->partner->image ?: ""),
                            "is_phone_verified" => 0,
                            "email_verified_at" => "",
                            "created_at" => $saleOrder->partner->created_at,
                            "updated_at" => $saleOrder->partner->write_date,
                            "email_verification_token" => "",
                            "phone" => $saleOrder->partner->mobile ?: $saleOrder->partner->phone ?: "",
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
                            "orders_count" => $ordersCount
                        ]
                    ];
                    $orderList[] = $values;
                }
            }
        }

        return [
            "list" => $orderList,
            "total_size" => $saleOrders->count(),
            "limit" => $limit,
            "offset" => $offset
        ];
    }

    public function getCompletedOrders(Request $request)
    {
        $user = auth()->user();
        $inputData = $request->all();
        $limit = $inputData['limit'];
        $offset = $inputData['offset'];

        $orderList = $this->getCompletedOrdersList($user->company_id, $limit, $offset);

        if ($user) {
            if (count($orderList) > 0) {
                return response()->json([
                    'response' => $orderList,
                    'message' => 'Sale Orders Found'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'No data Found!'
                ], Response::HTTP_NOT_FOUND);
            }
        } else {
            return response()->json([
                'response' => [],
                'message' => 'User Not Found!'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function getOrderDetails(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'order_id' => 'required|integer',
        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        }
        $userId = auth()->id();
        $requestData = $request->all();
        $orderId = $requestData['order_id'];
        $orderList = [];

        $user = User::find($userId);
        $saleOrder = SaleOrder::find($orderId);

        $productInfo = new CustomHelper();

        if ($user) {
            if ($saleOrder) {
                foreach ($saleOrder->saleOrderLines as $line) {
                    $desc = $line->product->description['en'] ? strip_tags($line->product->description['en']) : '';
                    // $taxes = $line->product->taxes;
                    $amount = ResCompany::first()->tax;

                    // foreach ($taxes as $tax) {
                    //     $amount = $tax->amount;
                    // }

                    $image = $line->product->image_attachment
                        ? '/storage/' . $line->product->image_attachment->id
                        : "";

                    $values = [
                        "id" => $line->id,
                        "product_id" => $line->product->id,
                        "order_id" => $orderId,
                        "price" => $line->price_total,
                        "product_details" => [
                            "id" => $line->product->id,
                            "name" => $line->product->name,
                            "description" => $desc,
                            "image" => $image,
                            // "price" => $productInfo->getProductProductPrice($line->product),
                            "price" => round($line->price_total / $line->product_uom_qty, 2),
                            "file_en" => null,
                            "file_ar" => null,
                            "variations" => [],
                            "add_ons" => [],
                            "extra_products" => "[]",
                            "required_ingredients" => [],
                            "removable_ingredients" => [],
                            "tax" => $amount,
                            "available_time_starts" => "",
                            "available_time_ends" => "",
                            "status" => 1,
                            "created_at" => $line->product->create_date,
                            "updated_at" => $line->product->write_date,
                            "attributes" => [],
                            "category_ids" => [],
                            "choice_options" => [],
                            "discount" => 0,
                            "discount_type" => "percent",
                            "tax_type" => "percent",
                            "set_menu" => 0,
                            "popularity_count" => 1,
                            "product_type" => "",
                            "slug" => $line->product->name,
                            "stock" => 0,
                            "unit" => null,
                            "addon_details" => [],
                            "removableIngredient_details" => [],
                            "translations" => []
                        ],
                        "variation" => $productInfo->getAttributeProductProductAsMannasat($line->product, 'en'),
                        "discount_on_product" => 0,
                        "discount_type" => "discount_on_product",
                        "quantity" => $line->product_uom_qty,
                        "tax_amount" => $line->price_tax,
                        "created_at" => $line->create_date,
                        "updated_at" => $line->write_date,
                        "add_on_ids" => [],
                        "variant" => [],
                        "add_on_qtys" => [],
                        "removable_ingredient_ids" => [],
                        "extra_products_details" => "[]",
                        "add_on_taxes" => [],
                        "add_on_prices" => [],
                        "add_on_tax_amount" => 0,
                        "review_count" => 0,
                        "is_product_available" => 1,
                        "delivery_time" => $saleOrder->delivery_date ? $saleOrder->delivery_date : null,
                        "delivery_date" => $saleOrder->delivery_date ? $saleOrder->delivery_date : null,
                        "preparation_time" => $line->product->preparing_time,
                        "add_ons" => null,
                        "item_details" => null
                    ];

                    $orderList[] = $values;
                }

                $deliveryman = [];
                if ($saleOrder->driver_id) {
                    $deliveryman = [
                        'id' => $saleOrder->driver_id,
                        'name' => $saleOrder->driver->name,
                        'phone' => $saleOrder->driver->mobile ?: "",
                        'email' => $saleOrder->driver->email ?: "",
                        'image' => $saleOrder->driver->team_image_attachment
                            ? '/storage/' . $saleOrder->driver->team_image_attachment
                            : "",
                    ];
                }
                return response()->json([

                    'response' => [
                        'details' => $orderList,
                        'deliveryman' => $deliveryman,
                    ],
                    'message' => 'Sale Order Details Found'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'No data Found!'
                ], Response::HTTP_NOT_FOUND);
            }
        } else {
            return response()->json([
                'response' => [],
                'message' => 'User Not Found!'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function getDeliveryMen(Request $request)
    {
        $user = User::find(auth()->id());
        $driverList = [];

        if ($user) {
            // dd($user->company_id);
            $drivers = ResPartner::where('is_driver', true)
                ->whereIn('company_id', [$user->company_id, null])
                ->get();

            if ($drivers->isNotEmpty()) {
                foreach ($drivers as $driver) {
                    $values = [
                        "id" => $driver->id,
                        "first_name" => $driver->name,
                        "last_name" => "",
                    ];
                    $driverList[] = $values;
                }
                return response()->json([
                    'response' => $driverList,
                    'message' => 'Drivers Found'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'No driver Found!'
                ], Response::HTTP_NOT_FOUND);
            }
        } else {
            return response()->json([
                'response' => [],
                'message' => 'User Not Found!'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function assignDeliveryMan(Request $request)
    {
        $requestData = $request->all();
        $userId = auth()->id();
        $driverId = $requestData['driver_id'];
        $orderTimeToBeReady = $requestData['order_time_to_be_ready'];
        $orderId = $requestData['order_id'];
        $old_driver_id = $requestData['old_driver_id'];
        $user = User::find($userId);
        if ($user) {

            $values = [
                'driver_id' => $driverId,
                'order_time_to_be_ready' => $orderTimeToBeReady,
                'assign_time_time' => now(),
            ];

            $saleOrder = SaleOrder::find($orderId);

            if ($saleOrder) {
                $saleOrder->update($values);
                return response()->json([
                    'message' => 'Sale Order Updated'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'Sale Order Not Found'
                ], Response::HTTP_NOT_FOUND);
            }
        } else {
            return response()->json([
                'response' => [],
                'message' => 'User Not Found!'
            ], Response::HTTP_NOT_FOUND);
        }
    }
    public function updateOrderStatus(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'order_id' => 'required|integer',
            'status' => 'required',
        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        }
        $requestData = $request->all();
        $saleOrderId = $requestData['order_id'];
        $orderStatus = $requestData['status'];

        if ($saleOrderId) {
            $saleOrder = SaleOrder::find($saleOrderId);

            if ($saleOrder) {
                $orderStatusId = null;

                if ($orderStatus == 'delivered') {
                    $orderStatusId = 7;
                } elseif ($orderStatus == 'out_for_delivery') {
                    $orderStatusId = 6;
                } elseif ($orderStatus == 'ready') {
                    $orderStatusId = 5;
                } elseif ($orderStatus == 'in_progress') {
                    $orderStatusId = 4;
                } elseif ($orderStatus == 'confirmed') {
                    $orderStatusId = 3;
                }

                $saleOrder->update(['order_status' => $orderStatusId]);

                return response()->json([
                    'message' => 'Sale Order Status Changed!'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'Sale Order Not Found!'
                ], Response::HTTP_NOT_FOUND);
            }
        } else {
            return response()->json([
                'response' => [],
                'message' => 'Sale Order Not Found!'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function cancelOrder(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'order_id' => 'required|integer',
        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        }
        $requestData = $request->all();
        $saleOrderId = $requestData['order_id'];

        if ($saleOrderId) {
            $saleOrder = SaleOrder::find($saleOrderId);

            if ($saleOrder) {
                $saleOrder->update(['state' => 'cancel']);

                return response()->json([
                    'message' => 'Sale Order Canceled!'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'Sale Order Not Found!'
                ], Response::HTTP_NOT_FOUND);
            }
        } else {
            return response()->json([
                'response' => [],
                'message' => 'Sale Order Not Found!'
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
