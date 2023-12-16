<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductTemplateAttributeValueResource\Pages;
use App\Filament\Resources\ProductTemplateAttributeValueResource\RelationManagers;
use App\Models\Tenant\ProductAttribute;
use App\Models\Tenant\ProductAttributeValue;
use App\Models\Tenant\ProductTemplate;
use App\Models\ProductTemplateAttributeLine;
use App\Models\Tenant\ProductTemplateAttributeValue;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Form;
use Filament\Resources\Table;
class ProductTemplateAttributeValueResource extends Resource
{

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
    protected static ?string $model = ProductTemplateAttributeValue::class;


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('p_a_value_id')
                    ->options(ProductAttributeValue::all()->pluck('name.en', 'id'))
                    ->disabledOn('edit')
                    ->required(),
                // Select::make('a_l_id')
                //     ->options(ProductTemplateAttributeLine::all()->pluck('productAttributeLine.productTemplate', 'id')->toArray())
                //     ->required(),
                Select::make('product_tmpl_id')
                    ->options(ProductTemplate::all()->pluck('name.en', 'id'))
                    ->disabledOn('edit')
                    ->nullable(),
                Select::make('attribute_id')
                    ->options(ProductAttribute::all()->pluck('name.en', 'id')->toArray())
                    ->disabledOn('edit')
                    ->nullable(),
                TextInput::make('price_extra')->numeric()->nullable(),
                Toggle::make('ptav_active')
                    ->disabledOn('edit')
                    ->nullable(),

            ]);
    }

    public static function table(Table $table): Table
    {
        $record = $_GET['record'] ?? null;

        return $table
            ->columns([
                TextColumn::make('value_name'),
                TextColumn::make('product_tmpl_id'),
                TextColumn::make('attribute.name'),
                TextColumn::make('price_extra'),
                TextColumn::make('ptav_active'),
            ])
            ->filters([
                Filter::make('template')
                    ->query(
                        fn (Builder $query): Builder =>
                        $record != null ? $query->where('product_tmpl_id', $record) : $query
                    )
                    ->default(),
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
            'index' => Pages\ListProductTemplateAttributeValues::route('/'),
            'create' => Pages\CreateProductTemplateAttributeValue::route('/create'),
            'edit' => Pages\EditProductTemplateAttributeValue::route('/{record}/edit'),
        ];
    }
 
    public static function getlModelLabel(): string
    {
        return __('Template Attribute Values');
    }
public static function getPluralModelLabel(): string
{
    return __('Template Attribute Values');
}
}
