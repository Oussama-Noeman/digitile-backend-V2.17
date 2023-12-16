<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DigitileOrderKitchenLineResource\Pages;
use App\Filament\Resources\DigitileOrderKitchenLineResource\RelationManagers;
use App\Models\Tenant\DigitileOrderKitchenLine;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Resources\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DigitileOrderKitchenLineResource extends Resource
{
    public static function canViewAny(): bool
    {
        return true; // b el project yalli 2abel kenet false
    }
    protected static ?string $model = DigitileOrderKitchenLine::class;

    // protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    public static function getNavigationGroup(): ?string
    {
        return __('Kitchen');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                TextInput::make('model_id')
                    ->numeric()
                    ->nullable(),
                TextInput::make('order_kitchen_id')
                    ->numeric()
                    ->nullable(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('state')
                    ->required(),
                TextInput::make('model_type')
                    ->nullable(),
                TextInput::make('notes')
                    ->nullable(),
                TextInput::make('order_status')
                    ->nullable(),
                TextInput::make('qtity')
                    ->label('Quantity')
                    ->numeric()
                    ->nullable(),
                DateTimePicker::make('date_order')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('order_kitchen_id'),
                Tables\Columns\TextColumn::make('model_id'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('state'),
                Tables\Columns\TextColumn::make('model_type'),
                Tables\Columns\TextColumn::make('qtity')
                    ->label('Quantity'),
                Tables\Columns\TextColumn::make('notes'),
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
            'index' => Pages\ListDigitileOrderKitchenLines::route('/'),
            'create' => Pages\CreateDigitileOrderKitchenLine::route('/create'),
            'edit' => Pages\EditDigitileOrderKitchenLine::route('/{record}/edit'),
        ];
    }

    public static function getlModelLabel(): string
    {
        return __('Digitile Order Kitchen Line');
    }
    public static function getPluralModelLabel(): string
    {
        return __('Digitile Order Kitchen Line');
    }
}
