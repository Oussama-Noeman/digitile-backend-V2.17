<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductCategoryResource\Pages;
use App\Filament\Resources\ProductCategoryResource\RelationManagers;
use App\Filament\Resources\ProductCategoryResource\RelationManagers\ProductCategoryDessertsRelationManager;
use App\Filament\Resources\ProductCategoryResource\RelationManagers\ProductCategoryDrinksRelationManager;
use App\Filament\Resources\ProductCategoryResource\RelationManagers\ProductCategoryLikedRelationManager;
use App\Filament\Resources\ProductCategoryResource\RelationManagers\ProductCategoryRelatedRelationManager;
use App\Filament\Resources\ProductCategoryResource\RelationManagers\ProductCategorySidesRelationManager;
use App\Models\Tenant\ProductCategory;
use Filament\Actions\Modal\Actions\Action;
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

class ProductCategoryResource extends Resource
{
    protected static ?string $model = ProductCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox';
    public static function getNavigationGroup(): ?string
    {
        return __('Product Management');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('parent_id')
                    ->relationship('parent', 'name')
                    ->options(ProductCategory::where('is_publish', true)->get()->pluck('name.en', 'id'))
                    ->visible(function (callable $get) {
                        $main = $get('is_main');
                        if (!$main) return true;
                        else return false;
                    })
                    ->translateLabel(),

                KeyValue::make('name')
                    ->schema([
                        TextInput::make('en')->translateLabel(),
                        TextInput::make('ar')->translateLabel(),
                    ])
                    ->required(),

                FileUpload::make('image')
                    ->disk('public')->directory('images/ProductCategory')
                    ->image()
                    // ->imageEditor()
                    // ->imageEditorAspectRatios([
                    //     '16:9',
                    //     '4:3',
                    //     '1:1',
                    // ])
                    ->required()
                    ->translateLabel(),

                Select::make('company_id')
                    ->label('Company')
                    ->relationship('company', 'name')
                    ->translateLabel(),

                Toggle::make('is_publish')->nullable()->translateLabel()->Label('Is published'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('parent.name.en')->label('parent category')->translateLabel(),
                TextColumn::make('company.name')->translateLabel(),
                TextColumn::make('name.en')->translateLabel(),
                ImageColumn::make('image')->translateLabel(),
                TextColumn::make('name.ar')->label('Name ar')->translateLabel(),
                ToggleColumn::make('is_publish')->translateLabel()->Label('Is published'),
            ])
            ->filters([
                // ... existing code ...
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ProductCategoryDrinksRelationManager::class,
            ProductCategorySidesRelationManager::class,
            ProductCategoryRelatedRelationManager::class,
            ProductCategoryLikedRelationManager::class,
            ProductCategoryDessertsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductCategories::route('/'),
            'create' => Pages\CreateProductCategory::route('/create'),
            'edit' => Pages\EditProductCategory::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('Categories');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Categories');
    }
}
