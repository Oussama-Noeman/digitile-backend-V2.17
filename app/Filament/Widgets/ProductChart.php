<?php

namespace App\Filament\Widgets;

use App\Models\Tenant\ProductProduct;
use App\Models\Tenant\SaleOrderLine;
use Carbon\Carbon;
use DateTime;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Support\Facades\Schema;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ProductChart extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static string $chartId = 'productChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Best Selling';
    protected int | string | array $columnSpan = 'full';


    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    public static function canView(): bool
    {
        return tenancy()->initialized;
    }
    protected function getOptions(): array
    {
        // $startDate = Carbon::now()->startOfMonth();
        // $endDate = Carbon::now()->endOfMonth();
        $startDate = Carbon::parse($this->filterFormData['date_start']);
        $endDate = Carbon::parse($this->filterFormData['date_end']);
        $orderLines = SaleOrderLine::where('created_at', '<=', $endDate)
            ->where('created_at', '>=', $startDate)
            ->get();
        // dd($orderLines);
        $productUomQtyArray = [];
        $productIdArray = [];

        foreach ($orderLines as $orderLine) {
            $productId = $orderLine->product_id;
            $qty = $orderLine->product_uom_qty;

            // Check if the product_id already exists in the associative array
            if (isset($productUomQtyArray[$productId])) {
                // If it exists, add the quantity to the corresponding product_uom_qty entry
                $productUomQtyArray[$productId] += $qty;
            } else {
                $product = ProductProduct::find($productId);
                if ($product) {
                    $product_name = $product->name;
                    $productIdArray[$productId] = $product_name;
                    $productUomQtyArray[$productId] = $qty;
                }
            }
        }

        $newArray = [
            'product_uom_qty' => array_values($productUomQtyArray),
            'product_name' => array_values($productIdArray),
        ];
        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Best Seller',
                    'data' => $newArray['product_uom_qty'],
                ],
            ],
            'xaxis' => [
                'categories' => $newArray['product_name'],
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
                    'vertical' => true,
                ],
            ],
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            DateTimePicker::make('date_start')
                // ->native(false)
                ->displayFormat('d/m/Y')
                ->default(now()->subMonth()),
            DateTimePicker::make('date_end')
                // ->native(false)
                ->displayFormat('d/m/Y')
                ->default(now()),
            // DatePicker::make('date_start')
            //     ->default(now()->subMonth()),
            // DatePicker::make('date_end')
            //     ->default(now()),
        ];
    }
}
