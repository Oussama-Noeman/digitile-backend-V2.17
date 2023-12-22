<?php

use App\Http\Controllers\Api\V1\Platform\SubscriberController;
use App\Http\Controllers\MainBanner1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('main-banners', [MainBanner1::class, 'getMainBanners']);
Route::post('new-subscriber', [SubscriberController::class, 'addSubscriber']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
