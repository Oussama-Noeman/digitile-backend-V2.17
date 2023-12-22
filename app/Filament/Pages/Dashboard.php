<?php

namespace App\Filament\Pages;



use App\Filament\Widgets\EarningStats;
use Filament\Pages\Dashboard as BasePage;
use Illuminate\Support\Facades\DB;

class Dashboard extends BasePage
{

    protected function getFooterWidgets(): array
    {

        //            $tables = DB::select('SHOW TABLES');
        $base = '';
        if (str_contains(DB::getDatabaseName(), 'tenant')) {
            $base = 'tenant';
        } else {
            $base = 'central';
        }

        if ($base == 'tenant') {
            return [
                //                StatsOverview::class,
                // Branch_VistorsChart::class,
                // VistorsPieWeeklyChart::class,

            ];
        } else {
            return [];
        }
    }
}
