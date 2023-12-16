<?php

namespace App\Filament\Resources\ProductPricelistResource\RelationManagers;

use App\Models\ProductCategory;
use App\Models\ProductPricelist;
use App\Models\ProductTemplate;
use App\Models\ResCompany;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductPricelistItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'productPricelistItems';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Select::make('company_id')
                    ->options(ResCompany::all()->pluck('name', 'id'))
                    ->relationship('company', 'name')
                    ->nullable(),
                Radio::make("applied_on")
                    ->options([
                        0 => "All",
                        1 => "Product Category",
                        2 => "Product"
                    ])
                    ->reactive()
                ,
                Select::make('categ_id')
                    ->options(ProductCategory::all()->pluck('name', 'id'))
                    ->relationship('productCategory', 'name')
                    ->nullable()
                    ->visible(function (callable $get) {

                        $main = $get('applied_on');

                        if ($main==1) {
                          
                            return true;
                        } else {
                          
                            return false;
                        }
                    })->reactive()
                ,
                Select::make('product_tmpl_id')
                    ->options(ProductTemplate::all()->pluck('name', 'id'))
                    ->relationship('productTemplate', 'name')
                    ->nullable()
                    ->visible(function (callable $get) {
                        $main = $get('applied_on');
                        if ($main == 2)
                            return true;
                        else
                            return false;
                    }),

                Toggle::make('active')->nullable(),
                TextInput::make('percent_price')->numeric()->nullable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('pricelist_id')
            ->columns([
                Tables\Columns\TextColumn::make('pricelist_id'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
}
