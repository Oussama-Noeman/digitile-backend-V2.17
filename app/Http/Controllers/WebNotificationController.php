<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant\User;

class WebNotificationController extends Controller
{

    //    public function __construct()
    //    {
    //        $this->middleware('auth');
    //    }
    //    public function index()
    //    {
    //        return view('home');
    //    }

    public function updateToken(Request $request)
    {
        auth()->user()->fcm_token = $request->token;
        auth()->user()->save();
        return response()->json(['Token successfully stored.']);
    }

    public function sendWebNotification(Request $request)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $FcmToken = User::whereNotNull('fcm_token')->pluck('fcm_token')->all();
        //        dd($FcmToken);
        $serverKey = 'AAAAvF0aNqI:APA91bGkmJ8GNCUs3vsuQ5qwblOxpzWsUNwWQ_YaJRbQyVtiIIfEECir6PYazld8BpN_-W8M_QVpAXoHDlaXIxaNQSG14FvEOyXWkMPVzjN9_z31DEWjZfCFKsaT4ET28bngsOQIxqlk';

        $data = [
            "registration_ids" => $FcmToken,
            "notification" => [
                //                "title" => $request->title,
                //                "body" => $request->body,

                "title" => "testing notifications",
                "body" => "my first notification",
            ]
        ];
        //        dd($data);
        $encodedData = json_encode($data);

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];
        //        dd($headers);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        //        dd($result);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        // FCM response
        //        dd($result);
        return $result;
    }

    //    public static function send_push_notif_to_users($data, $topic,$noti_id, composer )
    //    {
    //        $key = self::get_business_settings('push_notification_key');
    //
    //
    //        $url = "https://fcm.googleapis.com/fcm/send";
    //        $header = array(
    //            "authorization: key=" . $key . "",
    //            "content-type: application/json"
    //        );
    //
    //
    //
    //        $postdata = '{
    //                "registration_ids" : '. json_encode($topic) .',
    //                "mutable_content": true,
    //                "notification" : {
    //                    "title":"' . $data['title'] . '",
    //                    "body" : "' . $data['description'] . '",
    //                    "image" : "' . $data['image'] . '",
    //                    "notification_id" : "' . $noti_id . '",
    //                },
    //                "data" : {
    //                    "title":"' . $data['title'] . '",
    //                    "body" : "' . $data['description'] . '",
    //                    "image" : "' . $data['image'] . '",
    //                    "notification_id" : "' . $noti_id . '",
    //                }
    //            }';
    //
    //
    //
    //        $ch = curl_init();
    //        $timeout = 120;
    //        curl_setopt($ch, CURLOPT_URL, $url);
    //        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    //        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    //        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    //        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    //
    //        // Get URL content
    //        $result = curl_exec($ch);
    //        // close handle to release resources
    //        curl_close($ch);
    //
    //        return $result;
    //    }
}
