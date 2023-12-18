<?php

namespace App\Http\Controllers\Api\V1\Platform;

use App\Http\Controllers\Controller;
use App\Models\Tenant\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ContactUsController extends Controller
{
    public function getContactUs(Request $request)
    {
        $data = $request->all();
        if (isset($data["company_id"])) {
            $company_id = $data["company_id"];
            $contactUs = ContactUs::where('company_id', $company_id)->get()->toArray();
            if (!empty($contactUs)) {
                return response()->json([
                    'response' => $contactUs,
                    'message' => 'ContactUs found'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'ContactUs not found!'
                ], Response::HTTP_NOT_FOUND);
            }
        } else {
            return response()->json([
                'response' => [],
                'message' => 'No Comapny Defined!'
            ], Response::HTTP_NOT_FOUND);
        }
    }
    public function addContactUs(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'comment' => 'required',
            'company_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred, Failed To Added!'
            ], Response::HTTP_NOT_FOUND);
        } else {
            $data = $validator->validated();
            $name = $data['name'];
            $email = $data['email'];
            $comment = $data['comment'];
            $phone = $request->input('phone') ?? null;
            $company_id = $data['company_id'];
            $contactUs = ContactUs::create([
                'name' => $name,
                'email' => $email,
                'comment' => $comment,
                'phone' => $phone,
                'company_id' => $company_id
            ]);
            if ($contactUs) {
                return response()->json([
                    'message' => 'ContactUs Received'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'An error Occurred, Failed To Added!'
                ], Response::HTTP_NOT_FOUND);
            }
        }
    }
}
