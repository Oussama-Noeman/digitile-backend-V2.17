<?php

namespace App\Http\Controllers\Api\V1\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\Tenant\User;
use App\Models\Tenant\ProductProduct;
use App\Models\Tenant\DigitileKitchen;
use App\Models\Tenant\SaleOrder;
use App\Models\Tenant\SaleOrderLine;
use App\Utils\CustomHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KitchenController extends Controller
{
    public function getKitchenCurrentOrders(Request $request)
    {
        $user = auth()->user(); // Assuming user is authenticated

        if ($user) {
            $kitchenId = $user->partner->kitchen_id;
            // dd($user->partner);

            if ($kitchenId) {
                //                dd($user->partner->company_id);
                $orderList = $this->getKitchenOrders($user->partner->company_id, $kitchenId);

                if (!empty($orderList)) {
                    return response()->json([
                        'response' => $orderList,
                        'message' => 'Sale Orders Found'
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'message' => 'No data Found!'
                    ], Response::HTTP_NOT_FOUND);
                }
            } else {
                return response()->json([
                    'message' => 'Kitchen Not Found!'
                ], Response::HTTP_NOT_FOUND);
            }
        } else {
            return response()->json([
                'message' => 'User Not Found!'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function getKitchenOrders($companyId, $kitchenId)
    {
        $orderList = [];
        $helper = new CustomHelper();

        // Assuming SaleOrder and SaleOrderLine models exist and are appropriately linked

        if ($companyId) {
            $saleOrders = SaleOrder::where('company_id', $companyId)
                ->where('state', '!=', 'cancel')
                ->whereIn('sale_order_type_id', [1, 2])
                ->whereIn('order_status', ['3', '4', '5'])
                ->orderBy('created_at', 'DESC')
                ->get();
            if ($saleOrders->isNotEmpty()) {
                $deliveryProduct = ProductProduct::where('is_delivery', true)->first();
                foreach ($saleOrders as $saleOrder) {
                    $addProduct = false;
                    $orderLines = $saleOrder->saleOrderLines()->get();
                    foreach ($orderLines as $line) {
                        $product = $line->product;
                        if ($product->id != $deliveryProduct->id) {
                            if ($product->kitchen_id) {
                                if ($product->kitchen_id == $kitchenId) {
                                    $addProduct = true;
                                    break;
                                }
                            } else {
                                // Assuming $kitchenId has a default_kitchen property
                                if ($kitchenId->default_kitchen) {
                                    $addProduct = true;
                                    break;
                                }
                            }
                        }
                    }

                    if ($addProduct) {
                        $address = $saleOrder->resPartnerShipping->city . " " . $saleOrder->resPartnerShipping->street;

                        $ordersCount = 0;
                        $theOrderStatusId = '';

                        switch ($saleOrder->order_status) {
                            case "2":
                                $theOrderStatusId = "Draft";
                                break;
                            case "3":
                                $theOrderStatusId = "Confirmed";
                                break;
                            case "4":
                                $theOrderStatusId = "In Progress";
                                break;
                            case "5":
                                $theOrderStatusId = "Ready";
                                break;
                            default:
                                $theOrderStatusId = "Undefined"; // Or handle default case based on your needs
                                break;
                        }
                        $preparationTime = 0; // detail->get_preparation_time($saleOrder);
                        $preparationTimeStr = ($preparationTime == 0) ? "0" : (string)$preparationTime;
                        $values = [
                            "id" => $saleOrder->id,
                            "name" => $saleOrder->name,
                            "user_id" => null,
                            "order_amount" => 0.0,
                            "coupon_discount_amount" => 0,
                            "coupon_discount_title" => "",
                            "payment_status" => "unpaid",
                            "order_status" => $theOrderStatusId,
                            "order_time_to_be_ready" => null,
                            "assign_time_time" => null,
                            "total_tax_amount" => 0.0,
                            "payment_method" => "cash_on_delivery",
                            "transaction_reference" => "",
                            "delivery_address_id" => $saleOrder->partner_shipping_id,
                            "created_at" => $saleOrder->created_at,
                            "updated_at" => $saleOrder->updated_at,
                            "checked" => 1,
                            "delivery_man_id" => $saleOrder->driver_id,
                            "delivery_charge" => 0.0,
                            "order_note" => "",
                            "coupon_code" => "",
                            "order_type" => "",
                            "branch_id" => 1,
                            "callback" => "",
                            "delivery_date" => null,
                            "delivery_time" => null,
                            "extra_discount" => "0.00",
                            "delivery_address" => [
                                "id" => $saleOrder->partner_shipping_id,
                                "address_type" => null,
                                "contact_person_number" => $saleOrder->resPartnerShipping->phone,
                                "floor" => "",
                                "house" => "",
                                "road" => $saleOrder->resPartnerShipping->street,
                                "address" => $address,
                                "latitude" => $saleOrder->resPartnerShipping->partner_latitude,
                                "longitude" => $saleOrder->resPartnerShipping->partner_longitude,
                                "created_at" => $saleOrder->resPartnerShipping->created_at,
                                "updated_at" => $saleOrder->resPartnerShipping->updated_at,
                                "user_id" => $saleOrder->user_id,
                                "contact_person_name" => $saleOrder->resPartnerShipping->name
                            ],
                            "preparation_time" => $preparationTimeStr,
                            "table_id" => "",
                            "number_of_people" => "",
                            "table_order_id" => "",

                            "customer" => [
                                "id" => $saleOrder->partner_id,
                                "f_name" => $saleOrder->partner->name,
                                "l_name" => "",
                                "email" => $saleOrder->partner->email,
                                "user_type" => "",
                                "is_active" => 1,
                                "image" => $saleOrder->partner->team_image_attachment,
                                "is_phone_verified" => 0,
                                "email_verified_at" => "",
                                "created_at" => $saleOrder->partner->created_at,
                                "updated_at" => $saleOrder->partner->updated_at,
                                "email_verification_token" => "",
                                "phone" => $saleOrder->partner->phone,
                                "cm_firebase_token" => "",
                                "point" => 0,
                                "temporary_token" => "",
                                "login_medium" => "",
                                "wallet_balance" => "0.000",
                                "refer_code" => null,
                                "refer_by" => null,
                                "login_hit_count" => 0,
                                "is_temp_blocked" => 0,
                                "temp_block_time" => "null",
                                "orders_count" => null

                            ]
                        ];

                        $orderList[] = $values;
                    }
                }
            }
        }

        return $orderList;
    }

    public function getKitchenHistoryOrders(Request $request)
    {
        $user = auth()->user(); // Assuming you are using Laravel's default User model

        $validator = Validator::make($request->all(), [
            'limit' => 'required|integer',
            'offset' => 'required|integer',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        }

        $limit = $request->input('limit');
        $offset = $request->input('offset');

        if ($user) {
            $kitchenId = $user->partner->kitchen_id;

            if ($kitchenId) {
                $orderList = $this->getKitchenOrdersCompleted($user->company_id, $kitchenId, $limit, $offset);

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
                    'message' => 'Kitchen Not Found!'
                ], Response::HTTP_NOT_FOUND);
            }
        } else {
            return response()->json([
                'response' => [],
                'message' => 'User Not Found!'
            ], Response::HTTP_NOT_FOUND);
        }
    }
    public function getKitchenOrdersCompleted($company_id, $kitchen_id, $limit, $offset)
    {
        $order_list = [];
        $helper = new CustomHelper();
        if ($company_id) {
            $sale_orders = SaleOrder::where([
                ['state', '!=', 'cancel'],
                ['company_id', $company_id],
                ['order_status', '5']
            ])
                ->whereIn('sale_order_type_id', [1, 2])
                ->orderBy('created_at', 'desc')
                ->skip($offset)
                ->take($limit)
                ->get();
            // dd($sale_orders);


            if ($sale_orders->isNotEmpty()) {
                $deliveryProduct = ProductProduct::where('is_delivery', true)->first();
                foreach ($sale_orders as $sale_order) {
                    $add_product = false;
                    $order_lines = SaleOrderLine::where('order_id', $sale_order->id)->get();

                    foreach ($order_lines as $line) {
                        $product = $line->product;
                        if ($product->id != $deliveryProduct->id) {
                            if ($product->kitchen_id && $product->kitchen_id == $kitchen_id) {
                                $add_product = true;
                                break;
                            } elseif (!$product->kitchen_id && $kitchen_id->default_kitchen) {
                                $add_product = true;
                                break;
                            }
                        }
                    }

                    if ($add_product) {
                        $values = [
                            "id" => $sale_order->id,
                            "name" => $sale_order->name,
                            "user_id" => null,
                            "order_amount" => 0.0,
                            "coupon_discount_amount" => 0,
                            "coupon_discount_title" => "",
                            "payment_status" => "unpaid",
                            "order_status" => $this->getOrderStatus($sale_order->order_status),
                            "order_time_to_be_ready" => null,
                            "assign_time_time" => null,
                            "total_tax_amount" => 0.0,
                            "payment_method" => "cash_on_delivery",
                            "transaction_reference" => "",
                            "delivery_address_id" => null,
                            "created_at" => $sale_order->created_at,
                            "updated_at" => $sale_order->updated_at,
                            "checked" => 1,
                            "delivery_man_id" => null,
                            "delivery_charge" => 0.0,
                            "order_note" => "",
                            "coupon_code" => "",
                            "order_type" => "",
                            "branch_id" => 1,
                            "callback" => "",
                            "delivery_date" => null,
                            "delivery_time" => null,
                            "extra_discount" => "0.00",
                            "delivery_address" => [
                                "id" => null,
                                "address_type" => null,
                                "contact_person_number" => "",
                                "floor" => "",
                                "house" => "",
                                "road" => "",
                                "address" => "",
                                "latitude" => null,
                                "longitude" => null,
                                "created_at" => null,
                                "updated_at" => null,
                                "user_id" => null,
                                "contact_person_name" => null
                            ],
                            "preparation_time" => $helper->getPreparationTime($sale_order),
                            "table_id" => "",
                            "number_of_people" => "",
                            "table_order_id" => "",
                            "customer" => [
                                "id" => null,
                                "f_name" => null,
                                "l_name" => "",
                                "email" => "",
                                "user_type" => "",
                                "is_active" => 1,
                                "image" => " ",
                                "is_phone_verified" => 0,
                                "email_verified_at" => "",
                                "created_at" => null,
                                "updated_at" => null,
                                "email_verification_token" => "",
                                "phone" => "",
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
                                "orders_count" => null
                            ]
                        ];

                        $order_list[] = $values;
                    }
                }
            }
        }

        $returnValues = [
            "list" => $order_list,
            "total_size" => $sale_orders->count(),
            "limit" => $limit,
            "offset" => $offset
        ];

        return $returnValues;
    }
    private function getOrderStatus($orderStatus)
    {
        switch ($orderStatus) {
            case "2":
                return "Draft";
            case "3":
                return "Confirmed";
            case "4":
                return "In Progress";
            case "5":
                return "Ready";
            default:
                return "";
        }
    }
    public function updateOrderStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_detail_id' => 'required|integer',
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
        $orderDetailId = $requestData['order_detail_id'];
        $orderStatus = $requestData['status'];

        $saleOrderDetail = SaleOrderLine::find($orderDetailId);

        if ($saleOrderDetail) {
            if ($orderStatus == 'ready') {
                $orderStatusId = '5';

                $saleOrderDetail->update([
                    'order_status' => $orderStatusId,
                ]);




                if ($saleOrderDetail) {
                    $saleOrderDetail->update([
                        'order_status' => $orderStatusId,
                    ]);

                    $order = $saleOrderDetail->order;
                    // $notification = new YourNotificationClass();

                    // $messageName = "تجهيز صنف";
                    // $messageDescription = "لقد تم تجهيز الصنف " . $orderDetail->product->name . " التابع للطلبية رقم " . $order->name;

                    // $managers = $order->company->partners()
                    //     ->where('is_manager', true)
                    //     ->get();

                    // foreach ($managers as $manager) {
                    //     $managerUser = $manager->user;
                    //     if ($managerUser) {
                    //         $notification->sendNotification(auth()->user(), $managerUser, $messageName, $messageDescription, $order->id);
                    //     }
                    // }


                    $saleOrder = SaleOrder::find($saleOrderDetail->order_id);




                    if ($saleOrder) {
                        $ready = true;

                        foreach ($saleOrder->orderLines as $line) {
                            if ($line->order_status != '5') {
                                $ready = false;
                                break;
                            }
                        }

                        if ($ready) {
                            $saleOrder->update([
                                'order_status' => $orderStatusId,
                            ]);
                        }
                    }



                    return response()->json([
                        'response' => [],
                        'message' => 'Order Detail Status Changed!'
                    ], Response::HTTP_OK);
                }
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'Order Status Not Found!'
                ], Response::HTTP_NOT_FOUND);
            }
        } else {
            return response()->json([
                'response' => [],
                'message' => 'Order Detail Not Found!'
            ], Response::HTTP_NOT_FOUND);
        }
    }
    public function getOrderDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer',
            'kitchen_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        }
        $requestData = $request->all();
        $userId = auth()->id();
        $orderId = $requestData['order_id'];
        $kitchenId = $requestData['kitchen_id'];

        $productinfo = new CustomHelper();
        $orderList = [];

        $saleOrder = SaleOrder::find($orderId);

        if (!$kitchenId) {
            return response()->json([
                'message' => 'Kitchen Not Found!'
            ], Response::HTTP_NOT_FOUND);;
        }

        $kitchen = DigitileKitchen::find($kitchenId);
        if (!$kitchen) {
            return response()->json([
                'message' => 'No Kitchen!'
            ], Response::HTTP_NOT_FOUND);
        }

        $user = User::find($userId);
        if ($user) {
            if ($saleOrder) {
                $orderLines = SaleOrderLine::where('order_id', $saleOrder->id)->get();

                foreach ($orderLines as $line) {
                    $addProduct = false;

                    $deliveryProduct = ProductProduct::where('is_delivery', true)->first();

                    if ($deliveryProduct->id != $line->product_id) {
                        $product = ProductProduct::find($line->product_id);

                        if ($product->kitchen_id) {
                            if ($product->kitchen_id == $kitchenId) {
                                $addProduct = true;
                            }
                        } else {
                            if ($kitchen->default_kitchen) {
                                $addProduct = true;
                            }
                        }

                        if ($addProduct) {
                            $desc = strip_tags($product->description);
                            $desc = trim($desc);

                            $amount = 0.0;

                            switch ($line->order_status) {

                                case "2":
                                    $theorderStatusId = "Draft";
                                    break;
                                case "3":
                                    $theorderStatusId = "Confirmed";
                                    break;
                                case "4":
                                    $theorderStatusId = "In Progress";
                                    break;
                                case "5":
                                    $theorderStatusId = "Ready";

                                    break;
                                default:
                                    $theorderStatusId = "";
                            }

                            $preparingTime = $product->preparing_time ?: "0";
                            $values = [

                                "id" => $line->id,
                                "product_id" => $line->product_id,
                                "order_id" => $orderId,
                                "price" => 0.0,
                                "order_status" => $theorderStatusId,
                                "product_details" => [
                                    "id" => $line->product_id,
                                    "name" => $product->name,
                                    "description" => $desc,
                                    "notes" => $line->notes,
                                    "image" => "/web/content/" . ($line->product->image ?: ""),
                                    "price" => 0.0,
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
                                    "slug" => Str::slug($line->product->name['en']),
                                    "stock" => 0,
                                    "unit" => null,
                                    "addon_details" => [],
                                    "removableIngredient_details" => [],
                                    "translations" => []
                                ],



                                "variation" => $productinfo->getAttributeProductProductAsMannasat($line->product, 'en'),
                                "discount_on_product" => 0,
                                "discount_type" => "discount_on_product",
                                "quantity" => $line->qtity,
                                "tax_amount" => 0.0,
                                "created_at" => $line->created_at,
                                "updated_at" => $line->updated_at,
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
                                "delivery_time" => null,
                                "delivery_date" => null,
                                "preparation_time" => $preparingTime,
                                "add_ons" => null,
                                "item_details" => null
                            ];
                            $orderList[] = $values;
                        }
                    }
                }
                return response()->json([
                    'response' => $orderList,
                    'message' => 'Sale Order Details Found'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => 'No data Found!'
                ], Response::HTTP_NOT_FOUND);
            }
        } else {
            return response()->json([
                'message' => 'User Not Found!'
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
