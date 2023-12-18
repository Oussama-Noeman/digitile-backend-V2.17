<?php

namespace App\Http\Controllers\Api\V1\Platform;

use App\Http\Controllers\Controller;
use App\Models\Tenant\ResPartner;
use App\Models\Tenant\User;
use App\Utils\CustomHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isEmpty;

class AddressController extends Controller
{
    public function getListOfOtherAddresses(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        }
        $requestData = $request->all();
        $retailerUser = User::where('id', $requestData['user_id'])->first();

        $helper = new CustomHelper();
        $addressList = [];

        if ($retailerUser) {
            $partner = ResPartner::where('id', $retailerUser->partner_id)->first();

            if ($partner) {
                // $company_id = $retailerUser->company_id;
                // $lat = $partner->partner_latitude ?: 0;
                // $long = $partner->partner_longitude ?: 0;

                // $addressList[] = [
                //     "address_id" => $partner->id,
                //     "address_name" => $partner->name,
                //     "mobile" => $partner->mobile ?: "",
                //     "phone" => $partner->phone ?: "",
                //     "street_name" => $partner->street ?: "",
                //     "city_name" => $partner->city ?: "",
                //     "state_id" => $partner->state_id ?: 0,
                //     "state_name" => $partner->state_id_name ?: "",
                //     "near" => $partner->street2 ?: "",
                //     "lat" => $lat,
                //     "long" => $long,
                //     "can_delete" => false,
                //     "delivery_info" => $helper->calculForAddress($lat, $long, $company_id),
                // ];

                $lat = 0;
                $long = 0;

                $childAddresses = ResPartner::where('parent_id', $partner->id)->get();

                foreach ($childAddresses as $add) {
                    $lat = $add->partner_latitude ?: 0;
                    $long = $add->partner_longitude ?: 0;

                    $values = [
                        "address_id" => $add->id,
                        "address_name" => $add->name,
                        "mobile" => $add->mobile ?: "",
                        "phone" => $add->phone ?: "",
                        "street_name" => $add->street ?: "",
                        "city_name" => $add->city ?: "",
                        "state_id" => $add->state_id ?: 0,
                        "state_name" => $add->state_id_name ?: "",
                        "near" => $add->street2 ?: "",
                        "lat" => $lat,
                        "long" => $long,
                        "can_delete" => true,
                        "delivery_info" => $helper->calculForAddress($lat, $long),
                    ];

                    $addressList[] = $values;
                }

                return response()->json([
                    'response' => $addressList,
                    'message' => 'list other address'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'no other address found'
                ], Response::HTTP_NOT_FOUND);
            }
        } else {
            return response()->json([
                'response' => [],
                'message' => 'User Not Found'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function addDeliveryAddressFromDefault(Request $request)
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
        $userId = $requestData['user_id'];

        $retailerUser = User::find($userId);
        $detail = new CustomHelper();

        if ($retailerUser) {
            if ($retailerUser->partner) {
                $lat = $retailerUser->partner->partner_latitude ?? 0;
                $long = $retailerUser->partner->partner_longitude ?? 0;

                $returnCalcul = $detail->calculForAddress($lat, $long);
                if (!empty($returnCalcul)) {
                    $values = [
                        'name' => $retailerUser->partner->name,
                        'mobile' => $retailerUser->partner->mobile ?? '',
                        'phone' => $retailerUser->partner->phone ?? '',
                        'city' => $retailerUser->partner->city ?? '',
                        'street' => $retailerUser->partner->street ?? '',
                        'street2' => $retailerUser->partner->street2 ?? '',
                        'partner_latitude' => $lat,
                        'partner_longitude' => $long,
                        'parent_id' => $retailerUser->partner->id,
                        'is_client' => true,
                        'is_member' => false,
                        'is_driver' => false,
                        'is_manager' => false,
                        'type' => 'delivery',
                        // 'zone_id': zone,
                    ];


                    $newAddress = ResPartner::create($values);

                    if ($newAddress) {
                        return response()->json([
                            'response' => $newAddress->id,
                            'message' => 'New address created'
                        ], Response::HTTP_CREATED);
                    } else {
                        $response = [
                            'status' => 404,
                            'message' => 'Default address is out of zones',
                        ];
                        return response()->json([
                            'response' => [],
                            'message' => 'Default address is out of zones'
                        ], Response::HTTP_NOT_FOUND);
                    }
                } else {
                    return response()->json([
                        'response' => [],
                        'message' => 'No address found'
                    ], Response::HTTP_NOT_FOUND);
                }
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'No address found'
                ], Response::HTTP_NOT_FOUND);
            }
        } else {
            return response()->json([
                'response' => [],
                'message' => 'User not found'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function removeOtherAddress(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'address_id' => 'required'
        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        }
        $data = $request->all();
        $address = ResPartner::where('id', $data['address_id'])->first();
        $user = User::where('id', $data['user_id'])->first();

        if ($user) {
            if ($address) {
                if ($user->partner->id != $address->id) {
                    foreach ($user->partner->children as $userAddress) {
                        if ($userAddress->id == $address->id) {
                            try {
                                $address->delete();
                                return response()->json([
                                    'response' => [],
                                    'message' => 'address removed'
                                ], Response::HTTP_OK);
                            } catch (\Exception $e) {
                                return response()->json([
                                    'response' => [],
                                    'message' => 'address used. Can not be removed!'
                                ], Response::HTTP_OK);
                            }
                        }
                    }
                    return response()->json([
                        'response' => [],
                        'message' => 'address not found in user addresses list'
                    ], Response::HTTP_NOT_FOUND);
                } else {
                    return response()->json([
                        'response' => [],
                        'message' => 'Address can not be deleted!'
                    ], Response::HTTP_NOT_FOUND);
                }
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'address not found'
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
