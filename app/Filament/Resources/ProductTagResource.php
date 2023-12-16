<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductTagResource\Pages;
use App\Filament\Resources\ProductTagResource\RelationManagers;
use App\Models\Tenant\ProductTag;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Form;
use Filament\Resources\Table;

class ProductTagResource extends Resource
{
    protected static ?string $model = ProductTag::class;
    public static function canViewAny(): bool
    {
        return true;
    }
    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                KeyValue::make('name')
                    ->schema([
                        TextInput::make('en'),
                        TextInput::make('ar'),

                    ])
                    ->translateLabel()
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name.en')->searchable()->sortable()->translateLabel(),
                ColorColumn::make('color')->translateLabel(),
            ])

            ->filters([])
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
            'index' => Pages\ListProductTags::route('/'),
            'create' => Pages\CreateProductTag::route('/create'),
            'edit' => Pages\EditProductTag::route('/{record}/edit'),
        ];
    }

    public static function getlModelLabel(): string
    {
        return __('Product Tag');
    }
    public static function getPluralModelLabel(): string
    {
        return __('Product Tag');
    }
}
