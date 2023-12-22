<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\EarningChart;
use App\Filament\Widgets\EarningStats;
use App\Filament\Widgets\ProductChart;
use App\Filament\Widgets\SaleOrderReport;
use Filament\Pages\Dashboard as BasePage;
use Illuminate\Support\Facades\DB;

class Dashboard extends BasePage
{
    protected function getHeaderWidgets(): array
    {

        $base = '';
        if (str_contains(DB::getDatabaseName(), 'tenant')) {
            $base = 'tenant';
        } else {
            $base = 'central';
        }

        if ($base == 'tenant') {
            return [
                // EarningChart::class,
                // EarningStats::class,
                // ProductChart::class,
                // SaleOrderReport::class,
            ];
        } else {
            return [];
        }
    }
}
