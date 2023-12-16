<?php

namespace App\Providers;

use App\Filament\Resources\AboutUsMissionResource;
use App\Filament\Resources\AboutUsResource;
use App\Filament\Resources\AboutUsSliderResource;
use App\Filament\Resources\AboutUsValueResource;
use App\Filament\Resources\AboutUsVisionResource;
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
use App\Filament\Resources\ResCompanyResource;
use App\Filament\Resources\SubscriberResource;
use App\Filament\Resources\ZoneZoneResource;
use App\Models\Tenant\MainBanner1;
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
                    ...MainBanner1Resource::getNavigationItems(),
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
                    ...ZoneZoneResource::getNavigationItems(),
                    ...ResCompanyResource::getNavigationItems(),
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

                    // ...UserResource::getNavigationItems()
                ]);
            }
        });
    }
    
}
