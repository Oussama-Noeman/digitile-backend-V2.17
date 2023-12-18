<?php

use App\Http\Controllers\Api\V1\Platform\ProductController;
use App\Http\Controllers\Api\V1\VisitorApiController;
use App\Http\Controllers\Api\V1\ProductApiController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;


Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])
    ->group(function () {
        Route::group(['namespace' => 'Api'], function () {
            Route::post('/product-information', [ProductController::class, 'getProductInformationById']);
        });
    });
