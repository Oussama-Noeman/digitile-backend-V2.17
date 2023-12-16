<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductPricelistItemResource\Pages;
use App\Filament\Resources\ProductPricelistItemResource\RelationManagers;
use App\Models\Tenant\ProductCategory;
use App\Models\Tenant\ProductPricelist;
use App\Models\Tenant\ProductPricelistItem;
use App\Models\Tenant\ResCompany;
use App\Models\Tenant\ResCurrency;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Form;
use Filament\Resources\Table;
class ProductPricelistItemResource extends Resource
{
    public static function canViewAny(): bool
    {
        return false;
    }
    protected static ?string $model = ProductPricelistItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('pricelist_id')
                    ->options(ProductPricelist::all()->pluck('name', 'id'))
                    ->relationship('productPricelist', 'name')
                    ->required()
                    ->translateLabel(),

                Select::make('company_id')
                    ->options(ResCompany::all()->pluck('name', 'id'))
                    ->relationship('company', 'name')
                    ->nullable()
                    ->translateLabel(),

                Select::make('currency_id')
                    ->options(ResCurrency::all()->pluck('name', 'id'))
                    ->relationship('currency', 'name')
                    ->nullable()
                    ->translateLabel(),

                Select::make('categ_id')
                    ->options(ProductCategory::all()->pluck('name', 'id'))
                    ->relationship('productCategory', 'name')
                    ->nullable()
                    ->translateLabel(),

                TextInput::make('applied_on')->required()->translateLabel(),
                TextInput::make('base')->required()->translateLabel(),
                TextInput::make('compute_price')->required()->translateLabel(),
                TextInput::make('min_quantity')->numeric()->nullable()->translateLabel(),
                TextInput::make('fixed_price')->numeric()->nullable()->translateLabel(),
                TextInput::make('price_discount')->numeric()->nullable()->translateLabel(),
                TextInput::make('price_round')->numeric()->nullable()->translateLabel(),
                TextInput::make('price_surcharge')->numeric()->nullable()->translateLabel(),
                TextInput::make('price_min_margin')->numeric()->nullable()->translateLabel(),
                TextInput::make('price_max_margin')->numeric()->nullable()->translateLabel(),
                Toggle::make('active')->nullable()->translateLabel(),
                DateTimePicker::make('date_start')->nullable()->translateLabel(),
                DateTimePicker::make('date_end')->nullable()->translateLabel(),
                TextInput::make('percent_price')->numeric()->nullable()->translateLabel(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // ... existing code ...
            ])
            ->filters([
                // ... existing code ...
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
          ;
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
            'index' => Pages\ListProductPricelistItems::route('/'),
            'create' => Pages\CreateProductPricelistItem::route('/create'),
            'edit' => Pages\EditProductPricelistItem::route('/{record}/edit'),
        ];
    }    
   
    public static function getModelLabel(): string
    {
        return __('Price List Item');
    }
    
    public static function getPluralModelLabel(): string
    {
        return __('Price List Items');
    }
}
