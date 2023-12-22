<?php

namespace App\Filament\Widgets;

use App\Models\Tenant\SaleOrder;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Facades\Schema;

class EarningStats extends BaseWidget
{
    public static function canView(): bool
    {
        return tenancy()->initialized;
    }
    protected function getCards(): array
    {
        return [
            Card::make('Total Amount', function () {

                // dd($this->filter);s
                $orders = SaleOrder::get();
                if (!empty($orders)) {
                    $earning = 0;
                    foreach ($orders as $order) {
                        $earning += $order->amount_total;
                        // $earning += $order->amount_total - $order->amount_tax;
                    }
                    return "$" . $earning;
                }
                return 0;
            }),
            Card::make('Total Sold', function () {

                // dd($this->filter);s
                $orders = SaleOrder::get();
                if (!empty($orders)) {
                    $earning = 0;
                    foreach ($orders as $order) {
                        $earning += $order->amount_total - $order->amount_tax;
                    }
                    return "$" . $earning;
                }
                return 0;
            }),
            Card::make('Total Sold %', function () {
                $orders = SaleOrder::get()->toArray();

                if (!empty($orders)) {
                    $total_earning = 0;
                    $earning = 0;
                    foreach ($orders as $order) {
                        $total_earning += $order['amount_total'];
                        $earning += $order['amount_total'] - $order['amount_tax'];
                    }
                    // dd($earning);
                    if ($total_earning > 0) {
                        $percentage = ($earning * 100) / $total_earning;
                        return  $percentage . "%";
                    }
                }
                return 0;
            }),
            Card::make('Total Tax', function () {

                // dd($this->filter);s
                $orders = SaleOrder::get();
                if (!empty($orders)) {
                    $tax = 0;
                    foreach ($orders as $order) {
                        $tax += $order->amount_tax;
                    }
                    return "$" . $tax;
                }
                return 0;
            }),
            Card::make('Total Tax %', function () {
                $orders = SaleOrder::get()->toArray();
                if (!empty($orders)) {
                    $total_earning = 0;
                    $tax = 0;
                    foreach ($orders as $order) {
                        $total_earning += $order['amount_total'];
                        $tax +=  $order['amount_tax'];
                    }
                    // dd($earning);
                    if ($total_earning > 0) {
                        $percentage = ($tax * 100) / $total_earning;
                        return  $percentage . "%";
                    }
                }
                return 0;
            }),
        ];
    }
}
