<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductAttributeResource\Pages;
//use App\Filament\Resources\ProductAttributeResource\RelationManagers;
use App\Models\Tenant\ProductAttribute;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Form;
use Filament\Resources\Table;

class ProductAttributeResource extends Resource
{
    protected static ?string $model = ProductAttribute::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    public static function getNavigationGroup(): ?string
    {
        return __('Product Management');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                KeyValue::make('name')
                    ->translateLabel()
                    ->schema([
                        TextInput::make('en')->required(),
                        TextInput::make('ar')->required(),

                    ])->required(),


            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //                TextColumn::make('sequence'),
                //                TextColumn::make('create_variant'),
                //                TextColumn::make('display_type'),
                TextColumn::make('name.en')->label('Name'),
                TextColumn::make('name.ar')->label('Name ar'),
                //                TextColumn::make('visibility'),
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
            ProductAttributeResource\RelationManagers\ValuesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductAttributes::route('/'),
            'create' => Pages\CreateProductAttribute::route('/create'),
            'edit' => Pages\EditProductAttribute::route('/{record}/edit'),
        ];
    }
    public static function getlModelLabel(): string
    {
        return __(' Attributes');
    }
    public static function getPluralModelLabel(): string
    {
        return __(' Attributes');
    }
}
