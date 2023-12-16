<?php

namespace App\Filament\Resources\ProductCategoryResource\RelationManagers;


use App\Models\ProductProduct;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Form;
use Filament\Resources\Table;
class ProductCategorySidesRelationManager extends RelationManager
{
    protected static string $relationship = 'productCategorySides';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // ->recordTiltleAttribute('name')
            // ->recordTitle(fn (ProductProduct $record): string => "{$record->name['en']}")
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->badge()
                ->separator(',')
                ,
                Tables\Columns\ToggleColumn::make('default')
                ->disabled(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make('sides_id')
                ->preloadRecordSelect()
                ->recordSelectSearchColumns(['name->en'])
                // ->form(fn (AttachAction $action): array => [
                //     $action->getRecordSelect(),
                //     Forms\Components\TextInput::make('name.en'),
                // ])
                
                ,
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                // Tables\Actions\EditAction::make(),
            ]);
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         // ...
            //         Tables\Actions\DetachBulkAction::make(),
            //     ]),
            //     // Tables\Actions\BulkActionGroup::make([
            //     //     Tables\Actions\DeleteBulkAction::make(),
            //     // ]),
            // ])
            // ->emptyStateActions([
            //     Tables\Actions\AttachAction::make('dessert_id') ,

            //     // Tables\Actions\CreateAction::make(),
            // ]);
    }
}