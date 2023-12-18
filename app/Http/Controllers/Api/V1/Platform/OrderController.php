<?php

namespace App\Http\Controllers\Api\V1\Platform;

use App\Http\Controllers\Controller;
use App\Models\Tenant\DriverOrder;
use App\Models\Tenant\FirstOrder;
use App\Models\LatitudeLongitude;
use App\Models\Tenant\ProductProduct;
use App\Models\Tenant\ProductTemplate;
use App\Models\ResCompany;
use App\Models\Tenant\ResPartner;
use App\Models\Tenant\SaleOrder;
use App\Models\Tenant\SaleOrderLine;
use App\Models\Tenant\User;
use App\Models\ZoneZone;
use App\Utils\Tax;
use Exception;
use Illuminate\Http\Request;
use App\Utils\CustomHelper;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    private $quotation;

    public function placeOrderPublic(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'products' => 'required',
            'company_id' => 'required',
            'delivery_date' => 'required',
            'sale_order_type_id' => 'required',

        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        }
        $ProductInfo = new CustomHelper();
        $products = $request->input("products");

        // print($products);
        foreach ($products as $record) {
            $product = ProductProduct::where('id', $record['product_id'])->first();

            if (!$product) {

                return response()->json([
                    'response' => [],
                    'message' => 'Product not Found'
                ], Response::HTTP_NOT_FOUND);
            }

            $addons = $record['addons_note'];
            $addon_price = 0;

            if (!empty($addons)) {
                $addon_price = 0;

                foreach ($addons as $addon) {
                    $productAddon = ProductProduct::where('id', $addon['product_id'])->first();

                    if (!$productAddon) {
                        return response()->json([
                            'response' => [],
                            'message' => 'Addon not Found'
                        ], Response::HTTP_NOT_FOUND);
                    }
                    if ($addon['price'] != $ProductInfo->getProductProductPrice($productAddon)) {

                        return response()->json([

                            'response' => [],
                            'message' => 'Add On Price Not Correct'
                        ], Response::HTTP_NOT_FOUND);
                    }

                    $addon_price += $addon['price'];
                }
            }

            $priceUnit = $record['price'];
            if (($priceUnit - $addon_price) != $ProductInfo->getProductProductPrice($product)) {
                // print($ProductInfo->getProductProductPrice($product));
                return response()->json([
                    'response' => [],
                    'message' => 'Price Not Correct'
                ], Response::HTTP_NOT_FOUND);
            }
        }

        $company_id = $request->input("company_id");
        $boot = User::where('active', true)->first();
        $userTimezone = env('APP_TIMEZONE');

        $deliveryDateStr = $request->input("delivery_date");
        $dateFormat = "d/m/Y H:i:s";
        $deliveryDate = DateTime::createFromFormat($dateFormat, $deliveryDateStr, new DateTimeZone('UTC'));



        // Convert the delivery_date from the source timezone to the user_timezone
        $deliveryDate->setTimezone(new DateTimeZone($userTimezone));
        $deliveryDateNaive = $deliveryDate->setTimezone(new DateTimeZone('UTC'));
        $zone = $request->input("zone_id") ? $request->input("zone_id") : null;
        $this->quotation = SaleOrder::create([
            // check partner_id logic and partner_invoice_id partner_shipping_id
            "partner_id" => 1,
            "partner_invoice_id" => 1,
            "state" => 'draft',
            "company_id" => $company_id,
            "delivery_date" => $deliveryDateNaive,
            "sale_order_type" => $request->input("sale_order_type_id"),
            "partner_shipping_id" => 1,
            "user_id" => $boot->id,
            "name" => CustomHelper::generateOrderName(),
            "date_order" => date('Y-m-d H:i:s'),
            "sale_order_type_id" => 1,
            'order_status' => 1,
            'zone_id' => $zone,
            // pricelist_id setted null in the database
        ]);
        $amount_total = 0;
        $amount_untaxed = 0;
        $amount_tax = 0;
        foreach ($products as $record) {

            $price_total = 0;
            $product = ProductProduct::where('id', $record['product_id'])->first();
            $price = $product->lst_price;

            $addons = $record['addons_note'];
            $addon_price = 0;
            $notesAddon = "";

            if ($addons) {
                $addon_price = 0;

                foreach ($addons as $addon) {
                    $productAddon = ProductProduct::where('id', $addon['product_id'])->first();

                    $price += $productAddon->lst_price;
                    $notesAddon .= $productAddon->name['en'];
                }
            }




            if ($product) {
                $price_with_tax = Tax::computeProductProductTax($product, $price);

                $price_total = $record['quantity'] * $price_with_tax;
                $untaxed_price_total = $record['quantity'] * $price;
                $price_tax = $record['quantity'] * ($price_with_tax - $price);
                SaleOrderLine::create([
                    "order_id" => $this->quotation->id,
                    "product_id" => $product->id,
                    "product_uom_qty" => $record['quantity'],
                    'name' => $product->name['en'],
                    "price_unit" => $price,
                    "notes" => $record['notes'],
                    "note_addons" => json_encode($record['addons_note']),
                    "removable_ingredients_note" => json_encode($record['removable_ingredients_note']),
                    'state' => 'draft',
                    'order_status' => 1,
                    'price_total' => $price_total,
                    'price_reduce_taxexcl' => $price,
                    'price_reduce_taxinc' => $price_with_tax,
                    'price_tax' => $price_tax,
                ]);
                $amount_total = $amount_total + $price_total;
                $amount_untaxed = $amount_untaxed + $untaxed_price_total;
                $amount_tax = $amount_tax + $price_tax;
            } else {

                return response()->json([
                    'response' => [],
                    'message' => 'Product Not found'
                ], Response::HTTP_NOT_FOUND);
            }
        }

        if ($request->input("sale_order_type_id") == '1') {
            $deliveryFees = $request->input("delivery_fees");
            $deliveryProduct = ProductProduct::where('is_delivery', true)->first();

            if ($deliveryProduct) {


                $delivery_with_tax = $price_with_tax = Tax::computeProductProductTax($deliveryProduct, $deliveryFees);


                $delivery_price_tax = $delivery_with_tax - $deliveryFees;
                SaleOrderLine::create([
                    "order_id" => $this->quotation->id,
                    "product_id" => $deliveryProduct->id,
                    "product_uom_qty" => 1,
                    'name' => $deliveryProduct->name['en'],
                    "price_unit" => $deliveryFees,
                    "price_total" => $delivery_with_tax,
                    "price_reduce_taxexcl" => $deliveryFees,
                    "price_reduce_taxinc" => $delivery_with_tax,
                    "price_tax" => $delivery_price_tax,
                ]);

                $amount_total = $amount_total + $delivery_with_tax;

                $amount_untaxed = $amount_untaxed + $deliveryFees;
                $amount_tax = $amount_tax + $delivery_price_tax;
            }
        }
        $this->quotation->update([
            "amount_total" => $amount_total,
            "amount_untaxed" => $amount_untaxed,
            "amount_tax" => $amount_tax,
            "total_qty" => count($products),
        ]);
        $this->quotation->save();
        return response()->json([
            'response' => [],
            'message' => 'Order Received'
        ], Response::HTTP_OK);
    }
    public function placeOrderUser(Request $request)
    {
        $request->validate(['user_id' => 'required']);
        $ProductInfo = new CustomHelper();
        $user_id = $request->input('user_id');
        $products = $request->input("products");

        // print($products);
        foreach ($products as $record) {
            $product = ProductProduct::where('id', $record['product_id'])->first();

            if (!$product) {

                return response()->json([
                    'response' => [],
                    'message' => 'Product not Found'
                ], Response::HTTP_NOT_FOUND);
            }

            $addons = $record['addons_note'];
            $addon_price = 0;

            if (!empty($addons)) {
                $addon_price = 0;

                foreach ($addons as $addon) {
                    $productAddon = ProductProduct::where('id', $addon['product_id'])->first();

                    if (!$productAddon) {
                        return response()->json([
                            'response' => [],
                            'message' => 'Addon not Found'
                        ], Response::HTTP_NOT_FOUND);
                    }
                    if ($addon['price'] != $ProductInfo->getProductProductPrice($productAddon)) {

                        return response()->json([

                            'response' => [],
                            'message' => 'Add On Price Not Correct'
                        ], Response::HTTP_NOT_FOUND);
                    }

                    $addon_price += $addon['price'];
                }
            }

            $priceUnit = $record['price'];
            if (($priceUnit - $addon_price) != $ProductInfo->getProductProductPrice($product)) {
                // print($ProductInfo->getProductProductPrice($product));
                return response()->json([
                    'response' => [],
                    'message' => 'Price Not Correct'
                ], Response::HTTP_NOT_FOUND);
            }
        }

        $company_id = $request->input("company_id");

        $userTimezone = env('APP_TIMEZONE');

        $deliveryDateStr = $request->input("delivery_date");
        $dateFormat = "d/m/Y H:i:s";
        $deliveryDate = DateTime::createFromFormat($dateFormat, $deliveryDateStr, new DateTimeZone('UTC'));
        $user = User::find($user_id);
        $partner = $user->partner;
        if ($request->input("sale_order_type_id") == '1') {
            $shipping_id = $request->input('address_id');
        } else $shipping_id =  $partner->id;
        // Convert the delivery_date from the source timezone to the user_timezone
        $deliveryDate->setTimezone(new DateTimeZone($userTimezone));
        $deliveryDateNaive = $deliveryDate->setTimezone(new DateTimeZone('UTC'));
        $zone = $request->input("zone_id") ? $request->input("zone_id") : null;
        $this->quotation = SaleOrder::create([
            // check partner_id logic and partner_invoice_id partner_shipping_id
            "partner_id" => $partner->id,
            "partner_invoice_id" => 1,
            "state" => 'draft',
            "company_id" => $company_id,
            "delivery_date" => $deliveryDateNaive,
            "sale_order_type" => $request->input("sale_order_type_id"),
            "partner_shipping_id" => $shipping_id,
            "user_id" => $user_id,
            "name" => CustomHelper::generateOrderName(),
            "date_order" => date('Y-m-d H:i:s'),
            "sale_order_type_id" => $request->input('sale_order_type_id'),
            'order_status' => 2,
            'zone_id' => $zone,
            // pricelist_id setted null in the database
        ]);
        $amount_total = 0;
        $amount_untaxed = 0;
        $amount_tax = 0;
        foreach ($products as $record) {

            $price_total = 0;
            $product = ProductProduct::where('id', $record['product_id'])->first();
            $price = $product->lst_price;

            $addons = $record['addons_note'];
            $addon_price = 0;
            $notesAddon = "";

            if ($addons) {
                $addon_price = 0;

                foreach ($addons as $addon) {
                    $productAddon = ProductProduct::where('id', $addon['product_id'])->first();

                    $price += $productAddon->lst_price;
                    $notesAddon .= $productAddon->name['en'];
                }
            }




            if ($product) {
                $price_with_tax = Tax::computeProductProductTax($product, $price);

                $price_total = $record['quantity'] * $price_with_tax;
                $untaxed_price_total = $record['quantity'] * $price;
                $price_tax = $record['quantity'] * ($price_with_tax - $price);
                SaleOrderLine::create([
                    "order_id" => $this->quotation->id,
                    "product_id" => $product->id,
                    "product_uom_qty" => $record['quantity'],
                    'name' => $product->name['en'],
                    "price_unit" => $price,
                    "notes" => $record['notes'],
                    "note_addons" => json_encode($record['addons_note']),
                    "removable_ingredients_note" => json_encode($record['removable_ingredients_note']),
                    'state' => 'draft',
                    'order_status' => 2,
                    'price_total' => $price_total,
                    'price_reduce_taxexcl' => $price,
                    'price_reduce_taxinc' => $price_with_tax,
                    'price_tax' => $price_tax,
                ]);
                $amount_total = $amount_total + $price_total;
                $amount_untaxed = $amount_untaxed + $untaxed_price_total;
                $amount_tax = $amount_tax + $price_tax;
            } else {

                return response()->json([
                    'response' => [],
                    'message' => 'Product Not found'
                ], Response::HTTP_NOT_FOUND);
            }
        }

        if ($request->input("sale_order_type_id") == '1') {
            $deliveryFees = $request->input("delivery_fees");
            $deliveryProduct = ProductProduct::where('is_delivery', true)->first();

            if ($deliveryProduct) {


                $delivery_with_tax = $price_with_tax = Tax::computeProductProductTax($deliveryProduct, $deliveryFees);


                $delivery_price_tax = $delivery_with_tax - $deliveryFees;
                SaleOrderLine::create([
                    "order_id" => $this->quotation->id,
                    "product_id" => $deliveryProduct->id,
                    "product_uom_qty" => 1,
                    'name' => $deliveryProduct->name['en'],
                    "price_unit" => $deliveryFees,
                    "price_total" => $delivery_with_tax,
                    "price_reduce_taxexcl" => $deliveryFees,
                    "price_reduce_taxinc" => $delivery_with_tax,
                    "price_tax" => $delivery_price_tax,
                ]);

                $amount_total = $amount_total + $delivery_with_tax;

                $amount_untaxed = $amount_untaxed + $deliveryFees;
                $amount_tax = $amount_tax + $delivery_price_tax;
            }
        }
        $this->quotation->update([
            "amount_total" => $amount_total,
            "amount_untaxed" => $amount_untaxed,
            "amount_tax" => $amount_tax,
            "total_qty" => count($products),
        ]);
        $this->quotation->save();
        return response()->json([
            'response' => [],
            'message' => 'Order Received'
        ], Response::HTTP_OK);
    }
    public function checkIfAddressWithinZones(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required',
        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        }
        $requestData = $request->all();
        if (isset($requestData['company_id'])) {
            $company_id = $requestData['company_id'];
        } else {
            $company_id = null;
        }

        $lat = $requestData['lat'];
        $lng = $requestData['lng'];

        $helper = new CustomHelper();
        $values = $helper->calculForAddress($lat, $lng, $company_id);
        if (!empty($values)) {
            return response()->json([
                'response' => $values,
                'message' => 'Success'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'response' => [],
                'message' => 'Out Of Zones!'
            ], Response::HTTP_NOT_FOUND);
        }
    }


    public function addOtherAddress(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'lat' => 'required',
            'long' => 'required',
            'name' => 'required',
            'mobile' => 'required',
            'user_id' => 'required|integer',
        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        }
        $req = $request->all();

        $address = '';

        $user_id = $req['user_id'];
        $theuser = User::where('active', true)->where('id', $user_id)->first();
        if (!empty($theuser)) {
            //            if (isset($req['city'])) {
            //                $address = $req['city'];
            //            }
            //            if (isset($req['street'])) {
            //                if (!empty($address)) {
            //                    $address .= ' - ' . $req['street'];
            //                } else {
            //                    $address = $req['street'];
            //                }
            //            }
            //
            //            if (isset($req['near'])) {
            //                if (!empty($address)) {
            //                    $address .= ' - ' . $req['near'];
            //                } else {
            //                    $address = $req['near'];
            //                }
            //            }

            $values = [
                "name" => $req['name'],
                "mobile" => $req['mobile'],
                "phone" => isset($req['phone']) ? $req['phone'] : null,
                "city" => $req['city'] ?: "",
                "street" => $req['street'] ?: "",
                "street2" => $req['near'] ?: "",
                "partner_latitude" => $req['lat'],
                "partner_longitude" => $req['long'],
                "parent_id" => $theuser->partner->id,
                "is_client" => true,
                "is_member" => false,
                "is_driver" => false,
                "type" => 'delivery',
                "user_id" => $user_id,
            ];

            $newAddress = ResPartner::create($values);

            if ($newAddress) {
                return response()->json([
                    'response' => $newAddress->id,
                    'message' => 'new address created'
                ], Response::HTTP_CREATED);
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'address not added'
                ], Response::HTTP_NOT_FOUND);
            }
        } else {
            return response()->json([
                'response' => [],
                'message' => 'No User Found !'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function getCurrentOrders(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'user_id' => 'required|integer',
        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        }

        $requestData = $request->all();
        $retailerUser = User::find($requestData['user_id']);

        if ($retailerUser) {
            $quotations = SaleOrder::where([
                ['partner_id', '=', $retailerUser->partner_id],
                ['order_status', '!=', '7'],
                ['state', '!=', 'cancel']
            ])->orderBy('created_at', 'DESC')->get();

            $orders = [];

            $deliveryProduct = ProductProduct::where('is_delivery', true)->first();

            if ($quotations->isNotEmpty()) {
                foreach ($quotations as $order) {
                    $orderLocation = DriverOrder::where('order_id', $order->id)->get();

                    $savedLocation = $orderLocation->isNotEmpty();

                    switch ($order->order_status) {
                        case "2":
                            $orderStatus = "Draft";
                            break;
                        case "3":
                            $orderStatus = "Confirmed";
                            break;
                        case "4":
                            $orderStatus = "In Progress";
                            break;
                        case "5":
                            $orderStatus = "Ready";
                            break;
                        case "6":
                            $orderStatus = "Out For Delivery";
                            break;
                        case "7":
                            $orderStatus = "Delivered";
                            break;
                        default:
                            $orderStatus = "";
                    }

                    $orderStatusId = $order->order_status;

                    if (!$savedLocation && ($order->order_status == '5' || $order->order_status == '6')) {
                        $orderStatusId = "4";
                        $orderStatus = "In Progress";
                    }

                    $products = [];
                    $deliveryCharge = 0;

                    foreach ($order->saleOrderLines as $line) {
                        if ($order->saleOrderTypes == "1") {
                            if ($deliveryProduct->id == $line->product->id) {
                                $deliveryCharge = $line->price_total;
                            }
                        } else {
                            $deliveryCharge = 0;
                        }

                        if ($deliveryProduct->id != $line->product->id) {
                            $values = [
                                "product_id" => $line->product->id,
                                "product_name" => $line->product->productTemplate->name['en'],
                                "notes" => $line->notes ?: "",
                                "product_image" => "/web/content/" . $line->product->productTemplate->image,
                                "quantity" => (int) $line->product_uom_qty,
                                "price" => $line->price_unit,
                            ];
                            $products[] = $values;
                        }
                    }

                    $orders[] = [
                        "order_id" => $order->id,
                        "order_name" => $order->name,
                        "sale_order_type_id" => $order->saleOrderTypes->id,
                        "delivery_fees" => $deliveryCharge,
                        "order_date" => $order->date_order,
                        "amount" => $order->amount_total,
                        "currency_symbol" => $order->resCompany->resCurrency->symbol['ar'],
                        "currency_symbol_en" => $order->resCompany->resCurrency->symbol['en'],
                        "order_status" => $orderStatus,
                        "order_status_id" => $orderStatusId,
                        "products" => $products,
                    ];
                }

                return response()->json([
                    'response' => $orders,
                    'message' => 'List of orders found'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'No orders found'
                ], Response::HTTP_NOT_FOUND);
            }
        } else {
            return response()->json([
                'response' => [],
                'message' => 'User not found'
            ], Response::HTTP_NOT_FOUND);
        }
    }
    public function trackOrderStatus(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'order_id' => 'required|integer',
        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        }
        $req = $request->all();

        $orderId = $req['order_id'];
        $userId = $req['user_id'];

        $clientUser = User::find($userId);

        if ($clientUser) {
            $order = SaleOrder::find($orderId);

            $orderLocation = DriverOrder::where('order_id', $orderId)->get();

            $savedLocation = $orderLocation->isNotEmpty();

            if ($order) {
                if ($order->partner->id == $clientUser->partner->id) {
                    $orderStatus = $order->order_status;

                    switch ($orderStatus) {
                        case "2":
                            $orderStatusId = "Draft";
                            break;
                        case "3":
                            $orderStatusId = "Confirmed";
                            break;
                        case "4":
                            $orderStatusId = "In Progress";
                            break;
                        case "5":
                            $orderStatusId = "Ready";
                            break;
                        case "6":
                            $orderStatusId = "Out For Delivery";
                            break;
                        case "7":
                            $orderStatusId = "Delivered";
                            break;
                        default:
                            $orderStatusId = $orderStatus;
                    }

                    if (!$savedLocation && ($orderStatus == '5' || $orderStatus == '6')) {
                        $orderStatus = '4';
                        $orderStatusId = "In Progress";
                    }

                    $deliveryman = null;

                    if ($order->driver) {
                        $deliveryman = [
                            'id' => $order->driver->id,
                            'name' => $order->driver->name,
                            'phone' => $order->driver->mobile ?: "",
                            'email' => $order->driver->email ?: "",
                            'image' => "/web/content/" . ($order->driver->team_image_attachment ?? ""),
                        ];
                    }

                    $values = [
                        'order_status' => $orderStatusId,
                        'order_status_id' => $orderStatus,
                        'delivery_time' => $order->delivery_date,
                        'deliveryman' => $deliveryman,
                        'restaurant_location' => [
                            'lat' => $order->resCompany->resPartner->partner_latitude ?: 0,
                            'lng' => $order->resCompany->resPartner->partner_longitude ?: 0,
                        ],
                        'client_location' => [
                            'lat' => $order->partnerShipping->partner_latitude ?: 0,
                            'lng' => $order->partnerShipping->partner_longitude ?: 0,
                        ],
                    ];
                    return response()->json([
                        'response' => $values,
                        'message' => 'List of Messages'
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'response' => [],
                        'message' => 'Different User!'
                    ], Response::HTTP_NOT_FOUND);
                }
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'Order Not Found!'
                ], Response::HTTP_NOT_FOUND);
            }
        } else {
            return response()->json([
                'response' => [],
                'message' => 'Client Not Found'
            ], Response::HTTP_NOT_FOUND);
        }
    }
    public function getHistoryOrders(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required|integer',
        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        }
        $requestData = $request->all();
        $userId = $requestData['user_id'];

        $retailerUser = User::find($userId);

        if ($retailerUser) {
            $quotations = SaleOrder::where([
                ['partner_id', '=', $retailerUser->partner_id],
                ['order_status', '=', '7'],
            ])->orderBy('created_at', 'desc')->get();

            $orders = [];

            $deliveryProduct = ProductTemplate::where('is_delivery', true)->first();

            if ($quotations->isNotEmpty()) {
                foreach ($quotations as $order) {
                    $deliveryCharge = 0;
                    $products = [];

                    foreach ($order->saleOrderLines as $line) {
                        if ($order->sale_order_type == "1") {
                            if ($deliveryProduct->id == $line->product_id->id) {
                                $deliveryCharge = $line->price_total;
                            }
                        } else {
                            $deliveryCharge = 0;
                        }
                        if ($deliveryProduct->id != $line->product->id) {
                            $values = [
                                "product_id" => $line->product->id,
                                "product_name" => $line->product->productTemplate->name,
                                "notes" => $line->notes ?: "",
                                "product_image" => "/web/content/" . $line->product->productTemplate->image,
                                "quantity" => (int)$line->product_uom_qty,
                                "price" => round($line->total / (int)$line->product_uom_qty, 2),
                            ];
                            $products[] = $values;
                        }
                    }

                    $orders[] = [
                        "order_id" => $order->id,
                        "order_name" => $order->name,
                        "sale_order_type_id" => $order->saleOrderTypes->id,
                        "order_date" => $order->date_order,
                        "amount" => $order->amount_total,
                        "currency_symbol" => $order->resCompany->resCurrency->symbol['ar'],
                        "currency_symbol_en" => $order->resCompany->resCurrency->symbol['en'],
                        "order_status" => "Delivered",
                        "products" => $products,
                    ];
                }
                return response()->json([
                    'response' => $orders,
                    'message' => 'list of orders found'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'no orders found'
                ], Response::HTTP_NOT_FOUND);
            }
        } else {
            return response()->json([
                'response' => [],
                'message' => 'User not found'
            ], Response::HTTP_NOT_FOUND);
        }
    }
    public function getFirstOrderDiscount(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required|integer',
        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        }
        $requestData = $request->all();
        $userId = $requestData['user_id'];
        $user = User::find($userId);

        if ($user) {
            $sale = SaleOrder::where('partner_id', $user->partner->id)->first();
            $firstOrderDiscount = FirstOrder::find(1);
            // dd($firstOrderDiscount);
            if (!$sale) {
                $firstOrder = true;

                if ($firstOrderDiscount->type == '1') {
                    $discountVal = $firstOrderDiscount->amount;
                    $discountType = "Discount";
                    $discountTypeId = "1";
                } elseif ($firstOrderDiscount->type == '2') {
                    $discountVal = $firstOrderDiscount->amount;
                    $discountType = "Amount";
                    $discountTypeId = "2";
                } elseif ($firstOrderDiscount->type == '3') {
                    $discountVal = 0.0;
                    $discountType = "Free delivery";
                    $discountTypeId = "3";
                } else {
                    $discountVal = 0.0;
                    $discountType = 'None';
                    $discountTypeId = "0";
                }
            } else {
                $firstOrder = false;
                $discountVal = 0.0;
                $discountType = 'None';
                $discountTypeId = "0";
            }

            $vals = [
                "first_order" => $firstOrder,
                "discount_type_id" => $discountTypeId,
                "discount_type" => $discountType,
                "discount_val" => $discountVal
            ];



            return response()->json([
                'response' => $vals,
                'message' => 'user first order discount'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'response' => [],
                'message' => 'No data Found'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function cancelOrder(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'order_id' => 'required|integer',
        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        }
        $data = $request->all();
        $validate = Validator::make($request->all(), [
            'order_id' => 'required',
            'user_id' => 'required',
        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        }
        $saleOrderId = $data['order_id'];
        $userId = $data['user_id'];


        $user = User::find($userId);
        if ($user) {
            $saleOrder = SaleOrder::find($saleOrderId);

            if ($saleOrder && $saleOrder->partner->id == $user->partner->id) {
                if ($saleOrder->order_status == '2') {
                    $saleOrder->update([
                        'state' => 'cancel'
                    ]);

                    return response()->json([
                        'response' => [],
                        'message' => 'Sale Order Canceled!'
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'response' => [],
                        'message' => 'Can not be deleted!'
                    ], Response::HTTP_NOT_FOUND);
                }
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'Sale Order Not Found or Different User!'
                ], Response::HTTP_NOT_FOUND);
            }
        } else {
            return response()->json([
                'response' => [],
                'message' => 'User not found'
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
