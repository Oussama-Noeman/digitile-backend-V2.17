<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DigitileOrderKitchenResource\Pages;
use App\Filament\Resources\DigitileOrderKitchenResource\RelationManagers;
use App\Models\Tenant\DigitileOrderKitchen;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Resources\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DigitileOrderKitchenResource extends Resource
{
    public static function canViewAny(): bool
    {
        return true; // b el project yalli 2abel kenet false
    }
    protected static ?string $model = DigitileOrderKitchen::class;

    // protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    public static function getNavigationGroup(): ?string
    {
        return __('Kitchen');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('model_id')
                    ->numeric()
                    ->nullable(),
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('state')
                    ->required(),
                Forms\Components\TextInput::make('model_type')
                    ->nullable(),
                Forms\Components\TextInput::make('order_status')
                    ->nullable(),
                Forms\Components\DateTimePicker::make('date_order')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('model_id'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('state'),
                Tables\Columns\TextColumn::make('model_type'),
                Tables\Columns\TextColumn::make('order_status'),
                Tables\Columns\TextColumn::make('date_order'),
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
            'index' => Pages\ListDigitileOrderKitchens::route('/'),
            'create' => Pages\CreateDigitileOrderKitchen::route('/create'),
            'edit' => Pages\EditDigitileOrderKitchen::route('/{record}/edit'),
        ];
    }

    public static function getlModelLabel(): string
    {
        return __('Digitile Order Kitchen');
    }
    public static function getPluralModelLabel(): string
    {
        return __('Digitile Order Kitchen');
    }
}
