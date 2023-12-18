<?php

namespace App\Http\Controllers\Api\V1\Platform;

use App\Models\Tenant\ResCompany;
use App\Utils\CustomHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ZoneController extends Controller
{
    public function getZones()
    {
        $companies = ResCompany::all();
        $companiesZones = [];
        $detail = new CustomHelper();

        foreach ($companies as $company) {
            $values = $detail->getCompanyZones($company->id);
            $companiesZones[] = $values;
        }

        return  response()->json([
            'response' => $companiesZones,
            'message' => 'List of Zones Found'
        ], Response::HTTP_OK);
    }
}
