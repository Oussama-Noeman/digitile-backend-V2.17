<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\SaleOrderResource;
use App\Models\SaleOrder;
use Filament\Forms\Components\DatePicker;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Resources\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class SaleOrderReport extends BaseWidget
{
    public static function canView(): bool
    {
        return tenancy()->initialized;
    }
    protected int | string | array $columnSpan = 'full';

    public function getTableQuery(): Builder
    {
        return SaleOrderResource::getEloquentQuery()
            ->orderBy('created_at', 'desc');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name'),
            TextColumn::make('order_status')->label('status'),
            TextColumn::make('total_qty')->label('Qty'),
            TextColumn::make('amount_total')->label('Amount'),
            TextColumn::make('created_at'),
        ];
    }

    protected function getTableFilters(): array
    {
        if (Schema::hasTable('sale_orders')) {
            return [
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('From'),
                        DatePicker::make('To'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['From'], function (Builder $query, $date) {
                                return $query->whereDate('created_at', '>=', $date);
                            })
                            ->when($data['To'], function (Builder $query, $date) {
                                return $query->whereDate('created_at', '<=', $date);
                            });
                    }),
            ];
        } else {
            return [];
        }
    }
}
