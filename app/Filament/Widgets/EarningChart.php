<?php

namespace App\Filament\Widgets;

use App\Models\Tenant\SaleOrder;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class EarningChart extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static string $chartId = 'earningChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Earning Analysis';
    protected int | string | array $columnSpan = 'full';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $startDate = Carbon::now()->startOfYear();
        $endDate = Carbon::now()->endOfYear();

        $orders = SaleOrder::where('created_at', '<=', $endDate)
            ->where('created_at', '>=', $startDate)
            ->where('order_status', 3)
            ->get();
        $monthlyTotals = $orders->groupBy(function ($order) {
            return $order->created_at->month; // Group by the month of created_at
        })->map(function ($groupedOrders) {
            return $groupedOrders->sum('amount_total'); // Sum the amount_total for each month
        });

        $fullMonthlyTotals = [];
        for ($month = 1; $month <= 12; $month++) {
            $fullMonthlyTotals[] = $monthlyTotals->has($month) ? $monthlyTotals[$month] : 0;
        }
        // dd($fullMonthlyTotals);
        return [
            'chart' => [
                'type' => 'area',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'BasicBarChart',
                    'data' => $fullMonthlyTotals,
                ],
            ],
            'xaxis' => [
                'categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'colors' => ['#f59e0b'],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 3,
                    'horizontal' => true,
                ],
            ],
        ];
    }
}
