<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductWishlistResource\Pages;
use App\Filament\Resources\ProductWishlistResource\RelationManagers;
use App\Models\Tenant\ProductWishlist;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Form;
use Filament\Resources\Table;
class ProductWishlistResource extends Resource
{
    protected static ?string $model = ProductWishlist::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('partner_id')
                    ->relationship('partner', 'name')
                    ->label('Partner')
                    ->translateLabel()
                    ->required(),
                // Select::make('product_id')
                //     ->relationship('productProduct','default_code')
                //     ->label('Product')
                // ->required()
                // ,
                // Select::make('pricelist_id')
                //     ->relationship('productPricelist','name->en')
                //     ->label('Pricelist')
                //     ->required(),
                Select::make('product_template_id')
                    ->relationship('productTemplate', 'name')
                    ->label('Template')
                    ->translateLabel()
                    ->required(),
                TextInput::make('price')
                ->translateLabel()
                    ->numeric(),

                Toggle::make('active')
                ->translateLabel(),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
               TextColumn::make('id')
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListProductWishlists::route('/'),
            // 'create' => Pages\CreateProductWishlist::route('/create'),
            // 'edit' => Pages\EditProductWishlist::route('/{record}/edit'),
        ];
    }
    
    public static function getlModelLabel(): string
    {
        return __('Product WishList');
    }
public static function getPluralModelLabel(): string
{
    return __('Product WishList');
}
}