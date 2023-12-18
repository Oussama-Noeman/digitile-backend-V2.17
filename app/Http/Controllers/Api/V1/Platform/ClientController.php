<?php

namespace App\Http\Controllers\Api\V1\Platform;

use App\Http\Controllers\Controller;
use App\Models\Tenant\DriverChat;
use App\Models\Tenant\DriverChatImage;
use App\Models\Tenant\User;
use App\Utils\Base64;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    public function sendMessage(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'order_id' => 'required|integer',

        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An Error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        }
        $requestData = $request->all();
        if (!isset($requestData['message']) && !isset($requestData['images'])) {
            return response()->json([
                'response' => [],
                'message' => 'Undifined Message/Image !'
            ], Response::HTTP_NOT_FOUND);
        }
        $userId = $request['user_id'];

        $clientUser = User::where('id', $userId)->first();

        if (isset($requestData['message'])) {
            $message = $requestData['message'];
        } else {
            $message = null;
        }
        if ($clientUser) {
            $imagesList = [];
            if (isset($requestData['images'])) {
                $imagesList = $requestData['images'];
            }

            $driverChat = DriverChat::create([
                'order_id' => $requestData['order_id'],
                'message' => $message,
                'image_found' => count($imagesList) > 0,
                'client_user_id' => $clientUser->id,
            ]);
            // dd($driverChat->id);


            if (!empty($imagesList)) {
                $directory = 'images/clientline/';
                foreach ($imagesList as $record) {
                    $data = Base64::getDecode($directory, $record);
                    DriverChatImage::create([
                        'driver_chat_id' => $driverChat->id,
                        'image_attachment' => $data['file_path'],
                    ]);
                }
            }

            return response()->json([
                'response' => [],
                'message' => 'Message Received'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'response' => [],
                'message' => 'Client Not Found'
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
