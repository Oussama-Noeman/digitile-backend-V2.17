<?php

namespace App\Filament\Resources\MainPageSectionResource\RelationManagers;

use App\Models\ProductProduct;
use App\Models\ProductTemplate;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AssociateAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Form;
use Filament\Resources\Table;
class ProductsRelationManager extends RelationManager {
    protected static string $relationship = 'products';

    public static function form(Form $form): Form {
        return $form
            ->schema([

            ]);
    }

    public static function table(Table $table): Table {
        return $table
            //  ->recordTitleAttribute('name')
            // ->recordTitle(fn(ProductTemplate $record): string => "{$record->name['en']}")
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect() 
                    ->recordSelectSearchColumns(['name->en'])
                    
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ]);
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\DetachBulkAction::make(),
            //     ]),
            //     // Tables\Actions\BulkActionGroup::make([
            //     //     Tables\Actions\DeleteBulkAction::make(),
            //     // ]),
            // ])
            // ->emptyStateActions([
            //     Tables\Actions\AttachAction::make('product_id'),

            //     // Tables\Actions\CreateAction::make(),
            // ]);
    }
}
