<?php

namespace App\Filament\Resources\ProductCategoryResource\RelationManagers;

use App\Models\ProductProduct;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductCategoryDrinksRelationManager extends RelationManager
{
    protected static string $relationship = 'productCategoryDrinks';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
               
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            // ->recordTitleAttribute('name')
            ->recordTitle(fn (ProductProduct $record): string => "{$record->name['en']}")
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
                Tables\Actions\AttachAction::make('drink_id')
                ->preloadRecordSelect()
                ->recordSelectSearchColumns(['name->en'])

                ,
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // ...
                    Tables\Actions\DetachBulkAction::make(),
                ]),
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->emptyStateActions([
                Tables\Actions\AttachAction::make('dessert_id') ,

                // Tables\Actions\CreateAction::make(),
            ]);
    }
}