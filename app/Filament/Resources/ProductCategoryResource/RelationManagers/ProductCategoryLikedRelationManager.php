<?php

namespace App\Filament\Resources\ProductCategoryResource\RelationManagers;

use App\Models\ProductProduct;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Form;
use Filament\Resources\Table;
class ProductCategoryLikedRelationManager extends RelationManager
{
    protected static string $relationship = 'productCategoryLiked';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
             
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // ->recordTitleAttribute('name')
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
                Tables\Actions\AttachAction::make('liked_id')
                ->preloadRecordSelect()
                ->recordSelectSearchColumns(['name->en'])
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
