<?php

namespace App\Http\Controllers\Api\V1\Platform;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Tenant\DriverChat;
use App\Models\Tenant\DriverChatImage;
use App\Models\Tenant\DriverOrder;
use App\Models\Tenant\ResPartner;
use App\Models\Tenant\SaleOrder;
use App\Models\SaleOrderLineImage;
use App\Models\Tenant\User;
use App\Utils\Base64;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DriverChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        }
        $request->validate([
            'order_id' => 'required',
            'message' => 'required'
        ]);
        $requestData = $request->all();
        $id = $requestData['order_id'];
        $message = $requestData['message'];
        $images = $request->images;
        $user = auth()->user();

        $order = SaleOrder::where('id', $id)->first();

        if (!$order) {
            return response()->json(
                [
                    'response' => [],
                    'message' => "Order Not Found"
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        if ($order->sale_order_type_id == "1") {
            $driver = $order->driver_id;
            $driver = ResPartner::where('id', $driver)->where('is_driver', 1)->first();
            //            dd($driver);
            if (!$driver) {
                return response()->json(
                    [
                        'response' => [],
                        'message' => "Driver Not Found"
                    ],
                    Response::HTTP_NOT_FOUND
                );
            }

            //            dd($user->id);
            if ($driver->id == $user->id) {

                $client = $order->user_id;
                if (!$client) {
                    return response()->json(
                        [
                            'response' => [],
                            'message' => "Client not found"
                        ],
                        Response::HTTP_NOT_FOUND
                    );
                }

                if ($images) {
                    $res = true;
                } else {
                    $res = false;
                }
                $chatdriver = DriverChat::create([
                    "order_id" => $id,
                    "message" => $message,
                    "image_found" => $res,
                    "driver_user_id" => $driver->id,
                ]);

                if (!empty($images)) {
                    $directory = 'images/driverline/';
                    foreach ($images as $base64Data) {
                        $data = Base64::getDecode($directory, $base64Data);
                        DriverChatImage::create([
                            'driver_chat_id' => $chatdriver->id,
                            'image_attachment' => $data['file_path'],
                        ]);
                    }
                }
                return response()->json([
                    'response' => [],
                    'message' => "chat added",
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'response' => [],
                    'message' => "User Not Authenticated for this order",
                ], Response::HTTP_OK);
            }
        } else {
            return response()->json([
                'response' => [],
                'message' => "status order not Delivery",
            ], Response::HTTP_NOT_FOUND);
        }
    }
    public function getMessage(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'offset' => 'required',
            'limit' => 'required'
        ]);
        $order = $request->input('order_id');
        $limit = $request->input('limit');
        $offset = $request->input('offset');

        $chatlist = DriverChat::where('order_id', $order)
            ->orderBy('created_at', 'desc')
            ->select("*")
            ->offset($offset)
            ->limit($limit)
            ->get();

        if (!$chatlist->isEmpty()) {
            $driver = User::where('id', $chatlist[0]->driver_user_id)->first();
            $deliveryman = [
                "name" => $driver->name,
                "image" => $driver->image
            ];
            $total = 0;
            $message = [];

            foreach ($chatlist as $chat) {
                $total = $total + 1;
                $image = [];
                $imagechat = DriverChatImage::where('driver_chat_id', $chat->id)->get();
                if (!$imagechat->isEmpty()) {
                    foreach ($imagechat as $im) {
                        array_push($image, $im->image_attachment);
                    }
                } else {
                    $image = null;
                }
                $mess = [
                    "id" => $chat->id,
                    "conversation_id" => $chat->id,
                    "customer_id" => $chat->client_user_id,
                    "deliveryman_id" => $deliveryman,
                    "message" => $chat->message,
                    "attachment" => $image,
                    "created_at" => $chat->created_at,
                    "updated_at" => $chat->updated_at
                ];
                array_push($message, $mess);
            }
            //            dd($message);
            $ress = [
                "total_size" => $total,
                "limit" => $limit,
                "offset" => $offset,
                "messages" => $message
            ];
            return response()->json([
                'response' => $ress,
                'message' => "List of Messages",
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'response' => [],
                'message' => "No Messages",
            ], Response::HTTP_NOT_FOUND);
        }
    }
    public function isTrackedOrder(Request $request)
    {
        $requestData = $request->all();
        $orderId = $request->input('order_id');
        // $orderId = $requestData['order_id'];

        // Assuming 'driver_order_location_data' is the corresponding Eloquent model
        $orderLocations = DriverOrder::where('order_id', $orderId)->get();
        $order = SaleOrder::where('id', $orderId)->get();
        if ($order->isNotEmpty()) {

            if ($orderLocations->isNotEmpty()) {
                $isTracked = true;
            } else {
                $isTracked = false;
            }

            return response()->json([
                'response' => $isTracked,
                'message' => "is_tracked",
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'response' => [],
                'message' => "Order not found",
            ], Response::HTTP_NOT_FOUND);
        }
    }
    public function getMessages(Request $request)
    {
        $data = $request->all();

        $orderId = $data['order_id'];
        $offsetMessages = $data['offset'];
        $limitMessages = $data['limit'];
        $messageList = [];
        $userId = $data['user_id'];

        $clientUser = User::find($userId);

        if ($clientUser) {
            $messages = DriverChat::where('order_id', $orderId)
                ->orderBy('created_at', 'desc')
                ->limit($limitMessages)
                ->offset($offsetMessages)
                ->get();

            $totalSize = count($messages);
            $beirutTimezone = config('app.beirut_timezone'); // Adjust this according to your configuration

            foreach ($messages as $message) {
                $images = [];
                // dd( $message);
                foreach ($message->image as $image1) {
                    $imagePath = "/web/content/" . ($image1->image->id ?? '');
                    $images[] = $imagePath;
                }

                $clientValue = $message->client_user_id
                    ? [
                        "name" => $message->clientUser->partner->name,
                        "image" => "/web/content/" . ($message->clientUser->partner->team_member_image_attachment->id ?? ''),
                    ]
                    : null;

                $driverValue = $message->driver_user_id
                    ? [
                        "name" => $message->drivertUser->partner->name,
                        "image" => "/web/content/" . ($message->drivertUser->partner->team_member_image_attachment->id ?? ''),
                    ]
                    : null;

                $createDateBeirut = $message->created_at->timezone($beirutTimezone);
                $writeDateBeirut = $message->updated_at->timezone($beirutTimezone);

                $value = [
                    "id" => $message->id,
                    "conversation_id" => $message->id,
                    "customer_id" => $clientValue,
                    "deliveryman_id" => $driverValue,
                    "message" => $message->message,
                    "attachment" => count($images) > 0 ? $images : null,
                    "created_at" => $createDateBeirut,
                    "updated_at" => $writeDateBeirut,
                ];

                $messageList[] = $value;
            }

            $values = [
                "total_size" => $totalSize,
                "limit" => $limitMessages,
                "offset" => $offsetMessages,
                "messages" => $messageList,
            ];
            return response()->json([
                'response' => $values,
                'message' => "List of Messages",
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'response' => [],
                'message' => "Client not found",
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
