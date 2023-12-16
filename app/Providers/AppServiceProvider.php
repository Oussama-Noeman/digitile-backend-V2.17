<?php

namespace App\Providers;

use App\Filament\Resources\SubscriberResource;
use App\Filament\Resources\ZoneZoneResource;
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
                    // ...UserResource::getNavigationItems()

                ]);
            } else {
                return $builder->items([
                    NavigationItem::make('Dashboard')
                        ->label('Scanlira Dashboard')
                        ->icon('heroicon-o-home')
                        ->activeIcon('heroicon-s-home')
                        ->isActiveWhen(fn (): bool => request()->routeIs('filament.pages.dashboard'))
                        ->url(route('filament.pages.dashboard')),
                    ...SubscriberResource::getNavigationItems(),
                    ...ZoneZoneResource::getNavigationItems(),
                    // ...UserResource::getNavigationItems()
                ]);
            }
        });
    }
}
