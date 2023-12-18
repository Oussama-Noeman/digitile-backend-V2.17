<?php

namespace App\Http\Controllers\Api\V1\Platform;

use App\Http\Controllers\Controller;
use App\Models\Tenant\MailingContact;
use App\Utils\CustomHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class MailingContactController extends Controller
{

    public function placeNewsletter(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required',
        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        }
        $email = $request->input("email");
        if (CustomHelper::isValidEmail($email)) {
            $exists = MailingContact::where('email', $email)->exists();

            if ($exists) {
                return response()->json([
                    'response' => [],
                    'message' => 'subscription already exist'
                ], Response::HTTP_OK);
            } else {
                MailingContact::create([
                    'email' => $email,
                    'name' => $email,
                    // 'company_id' => auth()->user()->defaultCompany,
                    'email_normalized' => $email,

                ]);
                return response()->json([
                    'response' => [],
                    'message' => 'subscription created successfully'
                ], Response::HTTP_OK);
            }
        }
        return response()->json([
            'response' => [],
            'message' => 'email not valid!'
        ], Response::HTTP_OK);
    }
}
