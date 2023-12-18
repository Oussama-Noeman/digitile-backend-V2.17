<?php

namespace App\Http\Controllers\Api\V1\Platform;

use App\Http\Controllers\Controller;
use App\Models\Tenant\ResPartner;
use App\Models\Tenant\User;
use App\Utils\CustomHelper;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Mockery\Generator\StringManipulation\Pass\Pass;
use Throwable;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'login' => 'required',
            'password' => 'required',
            'company_id' => 'sometimes|nullable',

            'email' => 'sometimes',

            'longitude' => 'required',
            'latitude' => 'required',
        ]);
        $login = $request->input('login');
        $Participant = User::where('login', $login)->first();
        if ($Participant) {
            return response()->json([
                'response' => [],
                'message' => 'You can not have two users with the same login!',
            ], Response::HTTP_BAD_REQUEST);
        } else {
            $partner = new ResPartner();
            $partner->name = $request->input('name');
            $partner->email = ($request->input('email')) ?? "";
            $partner->city = ($request->input('city')) ?? "";
            $partner->mobile = $request->input('login');
            $partner->phone = ($request->input('phone')) ?? "";
            $partner->street = ($request->input('street')) ?? "";
            $partner->street2 = ($request->input('near') ?? "");
            $partner->is_client = true;
            $partner->partner_latitude = $request->input('latitude');
            $partner->partner_longitude = $request->input('longitude');
            $partner->active = true;
            $partner->save();

            $user = new User();
            $user->name = $request->input('name');
            $user->login = $request->input('login');
            $user->email = ($request->input('email')) ?? "";
            $user->password = $request->input('password');
            //            $user->company_id = $request->input('company_id');
            $user->partner_id = $partner->id;
            $user->save();
            $partner->user_id = $user->id;
            $partner->update();

            $authToken = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'response' => [
                    'created_id' => $user->id,
                    'token' => $authToken
                ],
                'message' => 'User Created'
            ], Response::HTTP_OK);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'params.login' => 'required',
            'params.password' => 'required',
            'params.company_id' => 'nullable',
        ]);

        if ($request->input('params.company_id')) {
            $credentials = [
                'login' => $request->input('params.login'),
                'password' => $request->input('params.password'),
                'company_id' => $request->input('params.company_id')
            ];
            //            dd('company found');
        } else {
            $credentials = [
                'login' => $request->input('params.login'),
                'password' => $request->input('params.password'),
            ];
            //            dd('no company');
        }

        if (!auth()->attempt($credentials)) {
            return response()->json([
                'message' => 'Wrong credentials',
            ], 500);
        } else {
            $user = auth()->user();
            $role = '';
            $authToken = $user->createToken('auth-token')->plainTextToken;
            if ($user->partner->is_chef) {
                $role = 'Chef';
            }
            if ($user->partner->is_manager) {
                $role = 'Manager';
            }
            if ($user->partner->is_driver) {
                $role = 'Driver';
            }
            if ($user->partner->is_client) {
                $role = 'Client';
            }
            $kitchen = $user->partner->kitchen_id;

            return response()->json([
                'response' => [
                    'session_id' => $authToken,
                    'uid' => $user->id,
                    'role' => $role,
                    'kitchen_id' => $kitchen
                ],
                'message' => 'login complete'
            ], Response::HTTP_OK);
        }
    }


    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        return response()->json([
            'response' => [],
            'message' => 'SUCCESS'
        ], Response::HTTP_OK);
    }

    public function change_password(Request $request)
    {
        $request->validate(
            [
                'password' => 'required|string'
            ]
        );
        $userid = $request->input('user_id');
        $password = $request->input('password');
        $user = User::where('id', $userid)->first();
        $user->password = $password;
        $user->save();
        $user->tokens()->delete();

        return response()->json([
            'response' => [],
            'message' => 'password changed'
        ], Response::HTTP_OK);
    }

    public function delete_account(Request $request)
    {
        $userid = $request->input('user_id');
        $user = User::where('id', $userid)->first();
        $user->password = "@E!@~!X!Isdfd drt23f34#|6%$# @*!@! @W!@*! @U!~(@OMDSOQSSS";
        $user->save();
        $user->tokens()->delete();
        return response()->json([
            'response' => [],
            'message' => 'Account deleted'
        ], Response::HTTP_OK);
    }

    public function updateUserProfile(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'email' => 'sometimes',
            'name' => 'sometimes|string',
            'city' => 'sometimes',
            'street' => 'sometimes',
            'near' => 'sometimes',
            'image' => 'sometimes',
        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An Error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        }
        $requestData = $request->all();
        $userId = $requestData['user_id'];
        $user = User::find($userId);

        $detail = new CustomHelper();

        if ($user && $user->partner) {
            //            dd(isset($requestData['email']));
            if (isset($requestData['email'])) {

                if ($user->email != $requestData['email']) {
                    $other = User::where('email', $requestData['email'])->first();
                    if (isset($requestData['email'])) {
                        if (!$detail->isValidEmail($requestData['email'])) {
                            return response()->json(['response' => [], 'message' => 'Email not valid!!'], Response::HTTP_NOT_FOUND);
                        }
                        if ($other) {
                            return response()->json(['response' => [], 'message' => ' Duplicated Email!!'], Response::HTTP_NOT_FOUND);
                        }
                        $user->update(['email' => $requestData['email']]);
                        $user->partner->update(['email' => $requestData['email']]);
                    }
                }
            } else {
                $user->update(['email' => $requestData['email']]);
            }
            if (isset($requestData['name']) && trim($requestData['name']) !== '') {
                $userFound = User::where('name', $requestData['name'])
                    ->where('id', '!=', $user->id)
                    ->first();
                if ($userFound) {
                    return response()->json(['response' => [], 'message' => 'Duplicated Name'], Response::HTTP_NOT_FOUND);
                }

                $user->update(['name' => $requestData['name']]);
                $user->partner->update(['name' => $requestData['name']]);
            }
            $user->partner->update(['city' => $requestData['city']]);
            $user->partner->update(['street' => $requestData['street']]);
            $user->partner->update(['street2' => $requestData['near']]);

            //            if (isset($requestData['email'])) {
            //                $user->update(['email' => $requestData['email']]);
            //                $user->partner->update(['email' => $requestData['email']]);
            //            }

            //            if (isset($requestData['city']) && trim($requestData['city']) !== '') {
            //                $user->partner->update(['city' => $requestData['city']]);
            //            }
            //
            //            if (isset($requestData['street']) && trim($requestData['street']) !== '') {
            //                $user->partner->update(['street' => $requestData['street']]);
            //            }
            //
            //            if (isset($requestData['near']) && trim($requestData['near']) !== '') {
            //                $user->partner->update(['street2' => $requestData['near']]);
            //            }

            if (isset($requestData['image'])) {
                if ($requestData['image'] !== '-1') {
                    $user->partner->update(['image_1920' => $requestData['image']]);
                    $responseMessage = 'Change image';
                } else {
                    $user->partner->update(['image_1920' => null, 'team_image_attachment' => null]);
                    // $responseMessage = 'Delete image';
                }
            }
            // } else {
            //     // $responseMessage = 'Nothing to update';
            // }
            return response()->json([
                'response' => [],
                'message' => 'profile Updated'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'response' => [],
                'message' => 'No profile found'
            ], Response::HTTP_NOT_FOUND);
        }
    }
    public function getUserProfile(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required|integer',
        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An Error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        }
        $requestData = $request->all();
        $user_id = $requestData['user_id'];
        $user = User::find($user_id);

        if ($user->partner) {
            $partner = $user->partner;

            switch (true) {
                case ($partner->is_client):
                    $role = "Client";
                    break;
                case ($partner->is_driver):
                    $role = "Driver";
                    break;
                case ($partner->is_chef):
                    $role = "Chef";
                    break;
                case ($partner->is_manager):
                    $role = "Manager";
                    break;
                default:
                    $role = "no role";
            }


            $vals = [
                "user_name" => $user->name,
                "user_login" => $user->login,
                "user_mobile" => $user->partner->mobile ?? "",
                "user_role" => $role,
                "company_id" => $user->company_id,
                "user_city_name" => $user->partner->city ?? "",
                "user_street_name" => $user->partner->street ?? "",
                "user_address_details" => $user->partner->street2 ?? "",
                "user_email" => $user->partner->email ?? "",
                "user_image" => "/web/content/" . ($user->partner->team_image_attachment ?? ""),
            ];

            return response()->json([
                'response' => $vals,
                'message' => 'profile Found'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'response' => [],
                'message' => 'No profile Found!'
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
