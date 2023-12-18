<?php

namespace App\Providers;

use App\Filament\Resources\AboutUsMissionResource;
use App\Filament\Resources\AboutUsResource;
use App\Filament\Resources\AboutUsSliderResource;
use App\Filament\Resources\AboutUsValueResource;
use App\Filament\Resources\AboutUsVisionResource;
use App\Filament\Resources\AdminUserResource;
use App\Filament\Resources\CalendarResource;
use App\Filament\Resources\CareerInformationResource;
use App\Filament\Resources\ContactUsResource;
use App\Filament\Resources\CouponResource;
use App\Filament\Resources\CustomerFeedbackResource;
use App\Filament\Resources\DigitileKitchenResource;
use App\Filament\Resources\DigitileOrderKitchenLineResource;
use App\Filament\Resources\DigitileOrderKitchenResource;
use App\Filament\Resources\DriverChatResource;
use App\Filament\Resources\FirstOrderResource;
use App\Filament\Resources\FreeDeliveryResource;
use App\Filament\Resources\HrApplicationResource;
use App\Filament\Resources\HrJobResource;
use App\Filament\Resources\MailingContactResource;
use App\Filament\Resources\MainBanner1Resource;
use App\Filament\Resources\MainBanner2Resource;
use App\Filament\Resources\MainBanner3Resource;
use App\Filament\Resources\MainPageSectionResource;
use App\Filament\Resources\MemberResource;
use App\Filament\Resources\OrderTripResource;
use App\Filament\Resources\ProductAttributeResource;
use App\Filament\Resources\ProductAttributeValueResource;
use App\Filament\Resources\ProductCategoryResource;
use App\Filament\Resources\ProductPricelistItemResource;
use App\Filament\Resources\ProductPricelistResource;
use App\Filament\Resources\ProductProductResource;
use App\Filament\Resources\ProductTagResource;
use App\Filament\Resources\ProductTemplateResource;
use App\Filament\Resources\ProductWishlistResource;
use App\Filament\Resources\ResCompanyResource;
use App\Filament\Resources\ResCurrencyResource;
use App\Filament\Resources\ResGroupResource;
use App\Filament\Resources\ResLangResource;
use App\Filament\Resources\ResPartnerResource;
use App\Filament\Resources\SaleOrderResource;
use App\Filament\Resources\SubscriberResource;
use App\Filament\Resources\UserResource;
use App\Filament\Resources\WebsiteFaqResource;
use App\Filament\Resources\ZoneZoneResource;
use App\Models\Tenant\MainBanner1;
use App\Models\Tenant\MainBanner3;
use App\Models\Tenant\ResCompany;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationItem;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Filament::navigation(function (NavigationBuilder $builder): NavigationBuilder {
            if (tenancy()->initialized) {

                return $builder->items([
                    NavigationItem::make('Dashboard')
                        ->icon('heroicon-o-home')
                        ->activeIcon('heroicon-s-home')
                        ->isActiveWhen(fn (): bool => request()->routeIs('filament.pages.dashboard'))
                        ->url(route('filament.pages.dashboard')),
                    ...AboutUsMissionResource::getNavigationItems(),
                    ...AboutUsResource::getNavigationItems(),
                    ...AboutUsSliderResource::getNavigationItems(),
                    ...AboutUsValueResource::getNavigationItems(),
                    ...AboutUsVisionResource::getNavigationItems(),
                    ...CalendarResource::getNavigationItems(),
                    ...CareerInformationResource::getNavigationItems(),
                    ...ContactUsResource::getNavigationItems(),
                    ...CouponResource::getNavigationItems(),
                    ...CustomerFeedbackResource::getNavigationItems(),
                    ...DigitileKitchenResource::getNavigationItems(),
                    ...DigitileOrderKitchenLineResource::getNavigationItems(),
                    ...DigitileOrderKitchenResource::getNavigationItems(),
                    ...DriverChatResource::getNavigationItems(),
                    ...FirstOrderResource::getNavigationItems(),
                    ...FreeDeliveryResource::getNavigationItems(),
                    ...HrApplicationResource::getNavigationItems(),
                    ...HrJobResource::getNavigationItems(),
                    ...MailingContactResource::getNavigationItems(),
                    ...MainBanner1Resource::getNavigationItems(),
                    ...MainBanner2Resource::getNavigationItems(),
                    ...MainBanner3Resource::getNavigationItems(),
                    ...MainPageSectionResource::getNavigationItems(),
                    ...MemberResource::getNavigationItems(),
                    ...OrderTripResource::getNavigationItems(),
                    ...ProductAttributeResource::getNavigationItems(),
                    ...ProductAttributeValueResource::getNavigationItems(),
                    ...ProductCategoryResource::getNavigationItems(),
                    ...ProductPricelistItemResource::getNavigationItems(),
                    ...ProductPricelistResource::getNavigationItems(),
                    ...ProductProductResource::getNavigationItems(),
                    ...ProductTagResource::getNavigationItems(),
                    ...ProductTemplateResource::getNavigationItems(),
                    ...ProductWishlistResource::getNavigationItems(),
                    ...ResCurrencyResource::getNavigationItems(),
                    ...ResGroupResource::getNavigationItems(),
                    ...ResLangResource::getNavigationItems(),
                    ...ResPartnerResource::getNavigationItems(),
                    ...SaleOrderResource::getNavigationItems(),
                    ...WebsiteFaqResource::getNavigationItems(),
                    ...ZoneZoneResource::getNavigationItems(),
                    ...ResCompanyResource::getNavigationItems(),
                    ...UserResource::getNavigationItems(),
                ]);
            } else {
                return $builder->items([
                    NavigationItem::make('Dashboard')
                        ->label('Digitile Dashboard')
                        ->icon('heroicon-o-home')
                        ->activeIcon('heroicon-s-home')
                        ->isActiveWhen(fn (): bool => request()->routeIs('filament.pages.dashboard'))
                        ->url(route('filament.pages.dashboard')),
                    ...SubscriberResource::getNavigationItems(),

                    ...AdminUserResource::getNavigationItems()
                ]);
            }
        });
    }
}
