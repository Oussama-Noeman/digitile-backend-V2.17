<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductAttributeValueResource\Pages;
use App\Filament\Resources\ProductAttributeValueResource\RelationManagers;
use App\Models\Tenant\ProductAttribute;
use App\Models\Tenant\ProductAttributeValue;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Factories\Relationship;
use Filament\Resources\Form;
use Filament\Resources\Table;

class ProductAttributeValueResource extends Resource
{
    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
    protected static ?string $model = ProductAttributeValue::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                KeyValue::make('name')
                    ->translateLabel()
                    ->schema([
                        TextInput::make('en'),
                        TextInput::make('ar'),

                    ])
                    ->required(),
                Select::make('attribute_id')
                    ->translateLabel()
                    ->relationship('attribute', 'name')
                    ->options(
                        ProductAttribute::get()->pluck('name.en', 'id')
                    )
                    ->createOptionForm([
                        KeyValue::make('name')
                            ->translateLabel()
                            ->schema([
                                TextInput::make('en'),
                                TextInput::make('ar'),

                            ])

                            ->required(),

                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('attribute.name.en')->label('Attributes'),
                TextColumn::make('name.en')->label('Values'),
                //                TextColumn::make('sequence'),
                //                ColorColumn::make('color'),
                //                ToggleColumn::make('is_custom'),
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
            'index' => Pages\ListProductAttributeValues::route('/'),
            'create' => Pages\CreateProductAttributeValue::route('/create'),
            'edit' => Pages\EditProductAttributeValue::route('/{record}/edit'),
        ];
    }

    public static function getlModelLabel(): string
    {
        return __('Product Attributes Values');
    }
    public static function getPluralModelLabel(): string
    {
        return __('Product Attributes Values');
    }
}
