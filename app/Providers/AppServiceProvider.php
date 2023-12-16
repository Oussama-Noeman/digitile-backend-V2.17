<?php

namespace App\Providers;

use App\Filament\Resources\AboutUsMissionResource;
use App\Filament\Resources\MainBanner1Resource;
use App\Filament\Resources\SubscriberResource;

use App\Models\Tenant\MainBanner1;
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
