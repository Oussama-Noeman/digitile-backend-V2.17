<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductPricelistResource\Pages;
use App\Filament\Resources\ProductPricelistResource\RelationManagers;
use App\Models\Tenant\ProductPricelist;
use App\Models\Tenant\ResCompany;
use App\Models\Tenant\ResCurrency;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Form;
use Filament\Resources\Table;
class ProductPricelistResource extends Resource
{
    protected static ?string $model = ProductPricelist::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    public static function getNavigationGroup(): ?string
    {
        return __('Promotion Management');
    } 

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // TextInput::make('sequence')->numeric()->nullable()->translateLabel(),
            Select::make('currency_id')->translateLabel()
                ->options(ResCurrency::all()->pluck('name.en', 'id'))
                ->relationship('currency', 'name')
                ->required(),
                Select::make('company_id')->translateLabel()
                ->options(ResCompany::all()->pluck('name', 'id'))
                ->relationship('company', 'name')
                ->required(),
                KeyValue::make('name')->translateLabel()
                ->schema([
                    TextInput::make('en'),
                    TextInput::make('ar'),

                ])->addable(false)
                ->deletable(false)
                ->editableKeys(false)
                ->required(),
                FileUpload::make('image')->translateLabel()
                ->disk('public')->directory('images/ProductPricelist')
                ->image()
                ->imageEditor()
                ->imageEditorAspectRatios([
                    '16:9',
                    '4:3',
                    '1:1',
                ])->required(),
            // TextInput::make('discount_policy')->nullable()->translateLabel(),
            
            Toggle::make('active')->nullable()->translateLabel(),
            // TextInput::make('code')->nullable()->translateLabel(),
            // Toggle::make('selectable')->nullable()->translateLabel(),
            Toggle::make('is_published')->nullable()->translateLabel(),
            // Toggle::make('is_promotion')->nullable()->translateLabel(),
            // Toggle::make('is_banner')->nullable()->translateLabel(),
            // Toggle::make('is_offer')->nullable()->translateLabel(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sequence')->sortable()->translateLabel()->translateLabel(),
                ImageColumn::make('image')->translateLabel(),
                TextColumn::make('currency.name')->sortable()->translateLabel(),
                TextColumn::make('company.name')->sortable()->translateLabel(),
                TextColumn::make('discount_policy')->sortable()->translateLabel(),
                TextColumn::make('name')->sortable()->translateLabel(),
                TextColumn::make('active')->sortable()->translateLabel(),
                TextColumn::make('code')->sortable()->translateLabel(),
                ToggleColumn::make('selectable')->translateLabel(),
                ToggleColumn::make('is_published')->translateLabel(),
                ToggleColumn::make('is_promotion')->translateLabel(),
                ToggleColumn::make('is_banner')->translateLabel(),
                ToggleColumn::make('is_offer')->translateLabel(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
           ;
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\ProductPricelistItemsRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductPricelists::route('/'),
            'create' => Pages\CreateProductPricelist::route('/create'),
            'edit' => Pages\EditProductPricelist::route('/{record}/edit'),
        ];
    }    
   
    public static function getlModelLabel(): string
    {
        return __('Price List');
    }
public static function getPluralModelLabel(): string
{
    return __('Price List');
}
}
