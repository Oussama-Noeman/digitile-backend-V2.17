<?php

namespace App\Http\Controllers;

use App\Models\Tenant\MainBanner1 as TenantMainBanner1;
use Illuminate\Http\Request;

class MainBanner1 extends Controller
{
    public function getMainBanners(Request $request)
    {
        $mainbanners = TenantMainBanner1::all();
        if ($mainbanners) {
            return json_encode($mainbanners);
        } else {
            return [
                'message' => 'd3asla',
            ];
        }
    }
}
