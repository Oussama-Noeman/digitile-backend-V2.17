<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Filament\Resources\CouponResource\RelationManagers;
use App\Models\Tenant\Coupon;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Resources\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    public static function getNavigationGroup(): ?string
    {
        return __('Promotion Management');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make("name")->translateLabel(),
                DateTimePicker::make("from_date")->translateLabel(),
                DateTimePicker::make("to_date")->translateLabel(),
                TextInput::make("nbre")
                    ->translateLabel()
                    ->numeric(),
                // Radio::make("discount_type")
                // ->translateLabel()
                //     ->options([
                //         'percentage' => "Discount Percentage",
                //         'amount' => "Discount Amount",
                //     ]),
                Select::make('discount_type')
                    ->translateLabel()
                    ->options([
                        'percentage' => " Percentage",
                        'amount' => " Amount",
                    ]),
                TextInput::make('amount')
                    ->label("Value")
                    ->translateLabel()
                    ->numeric()
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
        // ->bulkActions([
        //     Tables\Actions\BulkActionGroup::make([
        //         Tables\Actions\DeleteBulkAction::make(),
        //     ]),
        // ])
        // ->emptyStateActions([
        //     Tables\Actions\CreateAction::make(),
        // ]);
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
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
    public static function getlModelLabel(): string
    {
        return __('Coupon');
    }
    public static function getPluralModelLabel(): string
    {
        return __('Coupon');
    }
}
