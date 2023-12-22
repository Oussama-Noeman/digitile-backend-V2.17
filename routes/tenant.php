<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Platform\ProductController;
use App\Http\Controllers\MainBanner1;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Filament\Resources\CalendarResource;
use App\Http\Controllers\Api\V1\Platform\AuthController;
use App\Http\Controllers\Api\V1\Platform\ConfigApiController;

use App\Http\Controllers\Api\V1\Platform\EventController;

use App\Http\Controllers\Api\V1\Platform\DriverChatController;

use App\Http\Controllers\Api\V1\Platform\FaqController;
use App\Http\Controllers\Api\V1\Platform\JobController;
use App\Http\Controllers\Api\V1\Platform\KitchenController;
use App\Http\Controllers\Api\V1\Platform\MainController;

use App\Http\Controllers\Api\V1\Platform\DriverOrderController;
use App\Http\Controllers\Api\V1\Platform\WishlistController;
use App\Http\Controllers\Api\V1\Platform\ZoneController;
use App\Http\Controllers\Api\V1\Platform\AboutusApiController;
use App\Http\Controllers\Api\V1\Platform\AddressController;
use App\Http\Controllers\Api\V1\Platform\BannerApiController;
use App\Http\Controllers\Api\V1\Platform\CalendarController;
use App\Http\Controllers\Api\V1\Platform\MailingContactController;

use App\Http\Controllers\Api\V1\Platform\CategoryController;
use App\Http\Controllers\Api\V1\Platform\ClientController;
use App\Http\Controllers\Api\V1\Platform\ContactUsController;
use App\Http\Controllers\Api\V1\Platform\OrderController;
use App\Http\Controllers\Api\V1\Platform\SubscriberController;
use App\Http\Controllers\BillingController;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/
Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])
->group(function () {
    Route::get('/{record}/bill',[BillingController::class,'getbill'])->name('get.bill');
});

Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
   
    Route::prefix('api')->group(function () {
        Route::post('registration', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login'])->name('login');

        Route::post('all-products', [ProductController::class, 'getListOfProducts']);
        Route::post('check-if-address-within-zones', [OrderController::class, 'checkIfAddressWithinZones']);
        Route::post('newsletter', [MailingContactController::class, 'placeNewsletter']);

        Route::post('config', [ConfigApiController::class, 'configuration']);
        Route::post('legal-information', [ConfigApiController::class, 'legal_information']);

        Route::post('/product-information', [ProductController::class, 'getProductInformationById']);
        Route::post('/place-event', [EventController::class, 'placeEvent']);



        Route::post('search-product', [ProductController::class, 'searchProduct']);

        Route::post('/place-order-public', [OrderController::class, 'placeOrderPublic']);
        Route::post('/place-order-user', [OrderController::class, 'placeOrderUser']);
        Route::post('main-page-banner1', [BannerApiController::class, 'index1']);
        Route::post('main-page-banner2', [BannerApiController::class, 'index2']);
        Route::post('main-page-banner3', [BannerApiController::class, 'index3']);
        Route::post('main-page-section', [BannerApiController::class, 'page_section']);

        Route::get('main-page-banner/{company_id}', [BannerApiController::class, 'getMainBanner']);

        Route::post('about-us', [AboutusApiController::class, 'aboutus']);
        Route::post('about-us-value', [AboutusApiController::class, 'aboutus_value']);
        Route::post('about-us-mission', [AboutusApiController::class, 'aboutus_mission']);
        Route::post('about-us-vision', [AboutusApiController::class, 'aboutus_vision']);
        Route::post('about-us-slider', [AboutusApiController::class, 'aboutus_slider']);
        Route::post('team', [AboutusApiController::class, 'team']);
        Route::post('customer-feedback', [AboutusApiController::class, 'customer_feedback']);

        Route::post('get-contact-us', [ContactUsController::class, 'getContactUs']);
        Route::post('contact-us', [ContactUsController::class, 'addContactUs']);


        Route::post('all-category-post', [CategoryController::class, 'getCategoriesByCompany']);
        Route::post('category_product', [CategoryController::class, 'getAllCategoryProducts']);

        Route::post('wishlist', [WishlistController::class, 'getWishlist']);
        Route::post('add-to-wishlist', [WishlistController::class, 'addToWishlist']);
        Route::post('remove-from-wishlist', [WishlistController::class, 'removeFromWishlist']);
        Route::post('time-slot', [CalendarController::class, 'getTimeSlot']);
        Route::post('list-user-addresses', [AddressController::class, 'getListOfOtherAddresses']);
        Route::post('current-orders', [OrderController::class, 'getCurrentOrders']);

        //address
        Route::post('remove-other-address', [AddressController::class, 'removeOtherAddress']);

        Route::post('first-order-discount', [OrderController::class, 'getFirstOrderDiscount']);

        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('change-password', [AuthController::class, 'change_password']);
        Route::post('delete-account', [AuthController::class, 'delete_account']);
        Route::post('add-other-address', [OrderController::class, 'addOtherAddress']);
        Route::post('orders-history', [OrderController::class, 'getHistoryOrders']);

        Route::post('job-apply-form-http', [JobController::class, 'jobApplyForm']);
        Route::post('career-information-http', [JobController::class, 'getCareerInformation']);
        Route::post('job-positions-http', [JobController::class, 'getJobPositions']);
        Route::post('cv-apply-form-http', [JobController::class, 'cvApplyForm']);

        Route::post('client/cancel-order', [OrderController::class, 'cancelOrder']);

        Route::post('branches', [MainController::class, 'getBranches']);
        Route::post('message/get-message', [DriverChatController::class, 'getMessages']);

        Route::post('client/track-order-status', [OrderController::class, 'trackOrderStatus']);
        Route::post('update-profile', [AuthController::class, 'updateUserProfile']);
        Route::post('user-profile', [AuthController::class, 'getUserProfile']);

        //client
        Route::post('client/message/send/client', [ClientController::class, 'sendMessage']);
        Route::post('add-delivery-address-from-default', [AddressController::class, 'addDeliveryAddressFromDefault']);
        Route::post('faqs', [FaqController::class, 'getListOfFaqPublic']);
        Route::post('zones', [ZoneController::class, 'getZones']);

        Route::group(['middleware' => ['auth:sanctum']], function () {

            Route::post('kitchen/current-orders', [KitchenController::class, 'getKitchenCurrentOrders']);
            Route::post('kitchen/order-details', [KitchenController::class, 'getOrderDetails']);


            Route::post('delivery-man/config', [DriverOrderController::class, 'getConfig']);
            Route::post('delivery-man/record-location-data', [DriverOrderController::class, 'recordLocationData']);
            Route::post('delivery-man/order-details', [DriverOrderController::class, 'getOrderDetails']);
            Route::post('delivery-man/confirm-delivery', [DriverOrderController::class, 'confirmDelivery']);
            Route::post('delivery-man/update-order-status', [DriverOrderController::class, 'updateOrderStatus']);

            Route::post('branch/update-order-status', [MainController::class, 'updateOrderStatus']);
            Route::post('branch/cancel-order', [MainController::class, 'cancelOrder']);
            Route::post('branch/order-details', [MainController::class, 'getOrderDetails']);
            Route::post('branch/delivery-men', [MainController::class, 'getDeliveryMen']);
            Route::post('branch/orders/assign-delivery-man', [MainController::class, 'assignDeliveryMan']);
            Route::post('branch/current-orders', [MainController::class, 'getCurrentOrders']);
            Route::post('branch/config', [MainController::class, 'getConfig']);
            Route::post('branch/all-orders', [MainController::class, 'getAllOrders']);
            Route::post('branch/completed-orders', [MainController::class, 'getCompletedOrders']);

            Route::post('order/get-record-location-data', [DriverOrderController::class, 'getRecordLocationData']);
            Route::post('delivery-man/profile', [DriverOrderController::class, 'getUserProfile']);
            Route::post('delivery-man/current-orders', [DriverOrderController::class, 'getCurrentOrders']);
            Route::post('delivery-man/all-orders', [DriverOrderController::class, 'getAllOrders']);
            Route::post('delivery-man/update-payment-status', [DriverOrderController::class, 'updatePaymentStatus']);
            Route::post('delivery-man/update-profile', [DriverOrderController::class, 'updateProfile']);
            Route::post('delivery-man/config', [DriverOrderController::class, 'getDeliveryManConfig']);

            Route::post('delivery-man/message/send/deliveryman', [DriverChatController::class, 'sendMessage']);
            Route::post('delivery-man/message/send/get-message', [DriverChatController::class, 'getMessage']);
            Route::post('delivery-man/is-tracked-order', [DriverChatController::class, 'isTrackedOrder']);

            //Kitchen
            Route::post('kitchen/completed-orders', [KitchenController::class, 'getKitchenHistoryOrders']);
            Route::post('kitchen/update-order-detail-status', [KitchenController::class, 'updateOrderStatus']);
        });
        //subscriber 
    });

});


