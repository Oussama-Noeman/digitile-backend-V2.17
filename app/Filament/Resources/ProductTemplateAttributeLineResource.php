<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductTemplateAttributeLineResource\Pages;
use App\Filament\Resources\ProductTemplateAttributeLineResource\RelationManagers;
use App\Models\Tenant\ProductAttribute;
use App\Models\Tenant\ProductTemplate;
use App\Models\Tenant\ProductTemplateAttributeLine;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Form;
use Filament\Resources\Table;
class ProductTemplateAttributeLineResource extends Resource
{
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
    protected static ?string $model = ProductTemplateAttributeLine::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('product_tmpl_id')->relationship('productTemplate', 'id')
                    ->options(ProductTemplate::all()->pluck('name.en', 'id'))
                    ->required(),
                Select::make('attribute_id')->relationship('attribute', 'id')
                    ->options(ProductAttribute::all()->pluck('name.en', 'id')->toArray())
                    ->required(),
                Toggle::make('active')->nullable(),
                TextInput::make('value_count')->numeric()->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product_tmpl_id')
                    ->label('Product Template')->sortable(),
                TextColumn::make('attribute.name.en')->label('Attribute')->sortable(),
                ToggleColumn::make('active')->label('Active')->sortable(),
                TextColumn::make('value_count')->label('Value Count')->sortable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
           ;
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
            'index' => Pages\ListProductTemplateAttributeLines::route('/'),
            'create' => Pages\CreateProductTemplateAttributeLine::route('/create'),
            'edit' => Pages\EditProductTemplateAttributeLine::route('/{record}/edit'),
        ];
    }
    public static function getlModelLabel(): string
    {
        return __('Template Attribute Line');
    }
public static function getPluralModelLabel(): string
{
    return __('Template Attribute Line');
}
}
