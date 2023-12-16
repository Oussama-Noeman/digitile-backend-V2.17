<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FreeDeliveryResource\Pages;
use App\Filament\Resources\FreeDeliveryResource\RelationManagers;
use App\Models\Tenant\FreeDelivery;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Resources\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FreeDeliveryResource extends Resource
{
    protected static ?string $model = FreeDelivery::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    public static function getNavigationGroup(): ?string
    {
        return __('Promotion Management');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Toggle::make("active")->translateLabel(),
                TextInput::make("amount")->translateLabel()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextInputColumn::make('amount')->translateLabel(),
                Tables\Columns\ToggleColumn::make('active')->translateLabel(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
        // ->bulkActions([
        //     Tables\Actions\BulkActionGroup::make([
        //         // Tables\Actions\DeleteBulkAction::make(),
        //     ]),
        // ])
        // ->emptyStateActions([
        //     // Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListFreeDeliveries::route('/'),
            // 'create' => Pages\CreateFreeDelivery::route('/create'),
            'edit' => Pages\EditFreeDelivery::route('/{record}/edit'),
        ];
    }
    public static function getlModelLabel(): string
    {
        return __('Free Delivery');
    }
    public static function getPluralModelLabel(): string
    {
        return __('Free Delivery');
    }
}
