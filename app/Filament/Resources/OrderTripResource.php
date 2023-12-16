<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderTripResource\Pages;
use App\Filament\Resources\OrderTripResource\RelationManagers;
use App\Models\Tenant\OrdersTrip;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Form;
use Filament\Resources\Table;

class OrderTripResource extends Resource
{
    public static function canViewAny(): bool
    {
        return true;
    }
    protected static ?string $model = OrdersTrip::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function getNavigationGroup(): ?string
    {
        return __('Delivery');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('driver_id')
                    ->relationship('partner', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('reference'),
                TextInput::make('state'),
                DateTimePicker::make('delivered_date'),
                TextInput::make('total')
                    ->numeric(),
                TextInput::make('delivered_total')
                    ->numeric(),
                TextInput::make('rest_totla')
                    ->numeric(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrderTrips::route('/'),
            'create' => Pages\CreateOrderTrip::route('/create'),
            'edit' => Pages\EditOrderTrip::route('/{record}/edit'),
        ];
    }

    public static function getlModelLabel(): string
    {
        return __('Order Trip');
    }
    public static function getPluralModelLabel(): string
    {
        return __('Order Trip');
    }
}
