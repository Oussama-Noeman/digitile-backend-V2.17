<?php

namespace App\Http\Controllers\Api\V1\Platform;

use App\Http\Controllers\Controller;
use App\Models\Tenant\ProductProduct;
use App\Models\Tenant\ResPartner;
use App\Models\Tenant\SaleOrder;
use App\Models\Tenant\SaleOrderLine;
use App\Models\Tenant\SaleOrderLineImage;
use App\Models\Tenant\User;
use App\Utils\Base64;
use App\Utils\CustomHelper;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    public function placeEvent(Request $request)
    {
        $user_id = $request->input('user_id');

        $products = $request->input("products");
        $name = $request->input('name');
        $mobile = $request->input('mobile');
        if ($user_id) {
            $boot = $user = User::find($user_id);
            $partner = $user->partner;
        } else {
            $request->validate(['name' => 'required', 'mobile' => 'required']);
            $boot = User::where('active', true)->first();
            $partner = $this->addAnotherAddress($name, $mobile);
        }
        // print($products);
        foreach ($products as $record) {
            $product = ProductProduct::where('id', $record['product_id'])->where('active', 1)->first();

            // Decode the base64 data

            if (!$product) {

                return response()->json([
                    'response' => [],
                    'message' => 'Product not Found'
                ], Response::HTTP_NOT_FOUND);
            }

            $addons = $record['addons_note'];
            $addon_price = 0;

            if ($addons) {
                $addon_price = 0;

                foreach ($addons as $addon) {
                    $productAddon = ProductProduct::where('id', $addon['product_id'])->where('active', 1)->first();

                    if (!$productAddon) {
                        return response()->json([
                            'response' => [],
                            'message' => 'Addon not Found'
                        ], Response::HTTP_NOT_FOUND);
                    }
                    $addon_price += $addon['price'];
                }
            }
        }

        $company_id = $request->input("company_id");







        $quotation = SaleOrder::create([
            // check partner_id logic and partner_invoice_id partner_shipping_id
            "partner_id" => $partner->id,
            "state" => 'draft',
            "company_id" => $company_id,
            // "sale_order_type" => $request->input("sale_order_type_id"),
            "partner_shipping_id" => $partner->id,
            "user_id" => $boot->id,
            "name" => CustomHelper::generateOrderName(),
            "date_order" => date('Y-m-d H:i:s'),
            "sale_order_type_id" => 3,
            'order_status' => 1,
            "note" => $request->input('note'),
            // pricelist_id setted null in the database 
        ]);
        $amount_total = 0;
        foreach ($products as $record) {

            $price_total = 0;
            $product = ProductProduct::where('id', $record['product_id'])->where('active', 1)->first();
            $price = $product->lst_price;
            $addons = $record['addons_note'];
            $addon_price = 0;
            $notesAddon = "";

            if ($addons) {
                $addon_price = 0;

                foreach ($addons as $addon) {

                    $productAddon = ProductProduct::where('id', $addon['product_id'])->where('active', 1)->first();
                    $price += $productAddon->lst_price;

                    $notesAddon .=  $productAddon->name['en'];
                }
            }


            if ($product) {

                $price_total = $record['quantity'] * $price;
                $orderLine = SaleOrderLine::create([
                    "order_id" => $quotation->id,
                    "product_id" => $product->id,
                    "product_uom_qty" => $record['quantity'],
                    'name' => $product->name['en'],
                    "price_unit" => $price,
                    "notes" => $record['notes'],
                    "note_addons" => json_encode($record['addons_note']),
                    "removable_ingredients_note" => json_encode($record['removable_ingredients_note']),
                    'state' => 'draft',
                    'order_status' => 'draft',
                    'price_total' => $price_total,
                ]);
                $images = $record['images']; // Get the base64 image data from the request
                if (!empty($images)) {
                    // Define the directory path
                    $directory = 'images/orderlines/';

                    // Check if the directory exists
                    // if (!Storage::disk('public')->exists($directory)) {
                    //     // If it doesn't exist, create it
                    //     Storage::disk('public')->makeDirectory($directory);
                    // }

                    // Now, you can save your images
                    foreach ($images as $base64Data) {
                        // $filename = 'image_' . time() . '_' . uniqid() . '.png'; // Generate a unique filename
                        // $file_path = $directory . $filename; // Path within the storage/app directory
                        $data = Base64::getDecode($directory, $base64Data);
                        // $decodedData = base64_decode($base64Data);
                        // Storage::disk('public')->put($data['file_path'], $data['decoded_data']);
                        SaleOrderLineImage::create([
                            'image' => $data['file_path'], // Store the path in the database, not the full URL
                            'order_line_id' => $orderLine->id,
                        ]);
                    }
                }
                $amount_total = $amount_total + $price_total;
                $quotation->update(["amount_total" => $amount_total]);
                $quotation->save();
            } else {

                return response()->json([
                    'response' => [],
                    'message' => 'Product Not found'
                ], Response::HTTP_NOT_FOUND);
            }
        }

        $quotation->update(["amount_total" => $amount_total]);
        $quotation->save();
        return response()->json([
            'response' => [],
            'message' => 'Order Received'
        ], Response::HTTP_OK);
    }
    public function addAnotherAddress($name, $mobile)
    {
        $newAddress = ResPartner::create([
            "name" => $name,
            "mobile" => $mobile,
        ]);
        return $newAddress;
    }
}
