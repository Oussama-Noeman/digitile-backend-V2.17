<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductProductResource\Pages;
use App\Filament\Resources\ProductProductResource\RelationManagers;
use App\Models\ProductCategory;
use App\Models\Tenant\ProductProduct;
use App\Tables\Columns\TemplateName;
use Filament\Tables\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Form;
use Filament\Resources\Table;
class ProductProductResource extends Resource
{
    protected static ?string $model = ProductProduct::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    public static function getNavigationGroup(): ?string
    {
        return __('Product Management');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->schema([
                        KeyValue::make('name')
                            ->schema([
                                Textarea::make('en')
                                    ->rows(5),
                                Textarea::make('ar')
                                    ->rows(5),
                            ])
                            ->valueLabel('Name')
                            ->editableKeys(false)
                            ->keyLabel('Language')
                            ->deletable(false)
                            ->addable(false)
                            ->columnSpan(1)
                            ->required(),
                        FileUpload::make('image')
                            ->translateLabel()
                            ->disk('public')->directory('images/ProductProduct')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->required()
                    ])->columns(2),
                Section::make('')
                    ->schema([
                        Toggle::make('sale_ok')
                            ->default(true)
                            ->translateLabel()
                            ->columns(),

                        Toggle::make('active')
                            ->translateLabel()
                            ->default(true),

                        Toggle::make('app_publish')
                            ->translateLabel()
                            ->default(true),


                        Toggle::make('is_combo')
                            ->translateLabel()
                            ->nullable(),

                        Toggle::make('is_add_ons')
                            ->translateLabel()
                            ->nullable()
                            ->default(false),

                        Toggle::make('is_ingredient')
                            ->nullable()
                            ->translateLabel()
                            ->default(false),

                    ])->columns(6),

                Tabs::make('Label')
                    ->tabs([
                        Tabs\Tab::make('General Information')
                            ->translateLabel()
                            ->schema([
                                TextInput::make('type')
                                    ->translateLabel(),
                                // Select::make('categ_id')
                                //     ->relationship('category', 'name')
                                //     ->options(ProductCategory::where('id', true)->get()->pluck('name.en', 'id'))
                                //     ->required(),

                                Select::make('company_id')
                                    ->translateLabel()
                                    ->relationship('company', 'name')
                                    ->nullable(),

                                Section::make('description')
                                    ->translateLabel()
                                    // ->statePath('description')
                                    ->schema([
                                        Textarea::make('en'),
                                        Textarea::make('ar'),

                                    ])->columns(2),

                            ])->columns(2),
                        Tabs\Tab::make('Attributes and Variants')
                            ->translateLabel()
                            ->schema([
                                // ...
                            ]),
                        Tabs\Tab::make('Kitchen')
                            ->translateLabel()
                            ->schema([
                                Select::make('kitchen_id')
                                    ->translateLabel()
                                    ->relationship('kitchen', 'name')
                                    ->nullable(),
                                TextInput::make('preparing_time')
                                    ->translateLabel()
                                    ->numeric()
                                    ->nullable(),
                            ]),
                        Tabs\Tab::make('Add ons')
                            ->translateLabel()
                            ->schema([
                                // ...
                            ]),
                        Tabs\Tab::make('Complementary Products')
                            ->translateLabel()
                            ->schema([
                                // Toggle::make('drinks_mandatory')
                                //     ->nullable(),

                                Select::make('product_default_drink_id')
                                    ->label('Drinks')
                                    ->relationship('productDrink', 'name')
                                    ->translateLabel()
                                    ->nullable(),
                                // Select::make('default_drink_id')
                                //     ->label('Drink')
                                //     ->relationship('productRelatedDrinks', 'name')
                                //     ->nullable(),

                                TextInput::make('drinks_caption')
                                    ->translateLabel()
                                    ->required(),

                                // Toggle::make('sides_mandatory')
                                //     ->nullable(),
                                Select::make('product_default_side_id')
                                    ->translateLabel()
                                    ->label('Sides')
                                    ->relationship('productSide', 'name')
                                    ->nullable(),
                                // Select::make('default_sides_id')
                                //     ->relationship('productSides', 'name')
                                //     ->nullable(),
                                TextInput::make('sides_caption')
                                    ->translateLabel()
                                    ->required(),

                                TextInput::make('related_caption')
                                    ->translateLabel()
                                    ->required(),

                                TextInput::make('liked_caption')
                                    ->translateLabel()
                                    ->required(),

                                TextInput::make('desserts_caption')
                                    ->translateLabel()
                                    ->required(),
                            ]),
                    ]),


                TextInput::make('default_code')
                    ->translateLabel()
                    ->nullable(),

                TextInput::make('lst_price')
                    ->Label('Price')
                    ->translateLabel()
                    ->nullable()
                    ->numeric(),


                TextInput::make('discount')
                    ->translateLabel()
                    ->numeric()
                    ->required(),


            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // TextColumn::make('template_name'),
                TextColumn::make('template_name')->translateLabel(),
                TextColumn::make('name.en')->translateLabel(),
                TextColumn::make('name.ar')->translateLabel(),
                TextColumn::make('variant_name')->translateLabel(),
                ToggleColumn::make('default')->updateStateUsing(function ($state, $record) {
                    $product = $record;
                    $products = ProductProduct::where('product_tmpl_id', $product->product_tmpl_id)
                        ->where('active', 1)
                        ->get();
                    foreach ($products as $item) {
                        $item->default = false;
                        $item->save();
                    }
                    if ($state) {
                        $product->default = true;
                    } else {
                        $product->default = false;
                    }
                    $product->save();
                }),
                TextColumn::make('lst_price')->Label('Price')->translateLabel()

            ])
            ->filters([
                Filter::make('is_active')
                    ->query(fn (Builder $query): Builder => $query->where('active', 1))
                    ->default(),

            ])
            ->actions([
                ViewAction::make(),
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
            'index' => Pages\ListProductProducts::route('/'),
            'create' => Pages\CreateProductProduct::route('/create'),
            // 'edit' => Pages\EditProductProduct::route('/{record}/edit'),
        ];
    }

    public static function getlModelLabel(): string
    {
        return __('Product Variants');
    }
    public static function getPluralModelLabel(): string
    {
        return __('Product Variants');
    }
}
