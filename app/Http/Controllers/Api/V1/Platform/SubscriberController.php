<?php

namespace App\Http\Controllers\Api\V1\Platform;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use App\Utils\CustomHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class SubscriberController extends Controller
{
    public function addSubscriber(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'business_name' => 'required',
            'password' => 'required',
        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        };

        $requestData = $request->all();
        $helper = new CustomHelper();

        $nameAr = isset($requestData['name_ar']) ? $requestData['name_ar'] : null;
        $email_verified_at = isset($requestData['email_verified_at']) ? $requestData['email_verified_at'] : null;
        $status = isset($requestData['status']) ? $requestData['status'] : null;
        $adress = isset($requestData['adress']) ? $requestData['adress'] : null;
        $adress_ar = isset($requestData['adress_ar']) ? $requestData['adress_ar'] : null;


        $subscriber = Subscriber::create([
            'name' => $requestData['name'],
            'name_ar' => $nameAr,
            'email' => $requestData['email'],
            'phone' => $requestData['phone'],
            'business_name' => $requestData['business_name'],
            'email_verified_at' => $email_verified_at,
            'password' => $requestData['password'],
            'status' => $status,
            'adress' => $adress,
            'adress_ar' => $adress_ar,
        ]);

        $helper->createTenantAfterSubscriberCreation($subscriber);

        if ($subscriber) {
            return response()->json([
                'response' => [
                    'name' => $subscriber->name,
                    'email' => $subscriber->email,
                    'business_name' => $subscriber->business_name,
                    'phone' => $subscriber->phone,
                ],
                'message' => 'Subscriber Created Successfully with his Tenant ! '
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'response' => [],
                'message' => 'Failed to create'
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
