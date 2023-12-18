<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductTemplateResource\Pages;
use App\Filament\Resources\ProductTemplateResource\RelationManagers;
use App\Forms\Components\AttributeValues;
use App\Models\Tenant\ProductAttribute;
use App\Models\Tenant\ProductAttributeValue;
use App\Models\Tenant\ProductCategory;
use App\Models\Tenant\ProductProduct;
use App\Models\Tenant\ProductTemplate;
use App\Models\ProductTemplateAttributeLine;
use App\Models\ResCompany;
use Filament\Actions\ViewAction;
use Filament\Forms;
use App\Actions\ResetStars;
use App\Models\ProductTemplateAttributeValue;
use Dotenv\Exception\ValidationException;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\DateTimePicker;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\ButtonAction;
use Filament\Resources\Resource;
use Filament\Tables;
// use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Closure;
use function Laravel\Prompts\select;

class ProductTemplateResource extends Resource
{
    protected static ?string $model = ProductTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    public static function getNavigationGroup(): ?string
    {
        return __('Product Management');
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->id == 1;
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
                                    ->rows(5)
                                    ->required(),
                                Textarea::make('ar')
                                    ->rows(10)
                                    ->required(),
                            ])
                            ->valueLabel('Name')

                            ->keyLabel('Language')
                            ->translateLabel()

                            ->columnSpan(1)

                            ->required(),

                        FileUpload::make('image')
                            ->translateLabel()
                            ->disk('public')->directory('images/ProductTemplate')
                            ->image()
                        // ->imageEditor()
                        // ->imageEditorAspectRatios([
                        //     '16:9',
                        //     '4:3',
                        //     '1:1',
                        // ]),
                        // ->required(),

                    ])->columns(2),
                Section::make('')
                    ->schema([
                        Toggle::make('sale_ok')
                            ->default(true)
                            ->columns()
                            ->translateLabel(),

                        Toggle::make('active')
                            ->default(true)
                            ->translateLabel(),

                        Toggle::make('app_publish')
                            ->default(true)
                            ->translateLabel(),


                        Toggle::make('is_combo')
                            ->translateLabel()
                            ->nullable()
                            ->reactive(),

                        Toggle::make('is_add_ons')
                            ->nullable()
                            ->translateLabel()
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

                                // TextInput::make('type'),
                                Select::make('categ_id')
                                    ->reactive()
                                    ->translateLabel()
                                    ->afterStateUpdated(function ($state, callable $get, callable $set) {

                                        $set('default_drink_id', function (callable $get) {

                                            $category_id = $get('categ_id');
                                            if ($category_id) {
                                                $instances = DB::table('product_category_drinks')->where('category_id', $category_id)->pluck('drink_id');
                                                $prodArray = [];
                                                $products = ProductProduct::whereIn('id', $instances)->get()->pluck('id')->toArray();
                                                foreach ($products as $key => $value) {
                                                    $prodArray[] = $products[$key];
                                                }
                                                return $prodArray;
                                            }
                                        });
                                        $set('sides_products_id', function (callable $get) {
                                            $category_id = $get('categ_id');
                                            if ($category_id) {
                                                $instances = DB::table('product_category_related_products')->where('category_id', $category_id)->pluck('related_id');
                                                $prodArray = [];
                                                $products = ProductProduct::whereIn('id', $instances)->get()->pluck('id')->toArray();
                                                foreach ($products as $key => $value) {
                                                    $prodArray[] = $products[$key];
                                                }
                                                return $prodArray;
                                            }
                                        });
                                        $set('related_products_id', function (callable $get) {
                                            $category_id = $get('categ_id');
                                            if ($category_id) {
                                                $instances = DB::table('product_category_sides')->where('category_id', $category_id)->pluck('sides_id');
                                                $prodArray = [];
                                                $products = ProductProduct::whereIn('id', $instances)->get()->pluck('id')->toArray();
                                                foreach ($products as $key => $value) {
                                                    $prodArray[] = $products[$key];
                                                }
                                                return $prodArray;
                                            }
                                        });
                                        $set('liked_products_id', function (callable $get) {
                                            $category_id = $get('categ_id');
                                            if ($category_id) {
                                                $instances = DB::table('product_category_liked_products')->where('category_id', $category_id)->pluck('liked_id');
                                                $prodArray = [];
                                                $products = ProductProduct::whereIn('id', $instances)->get()->pluck('id')->toArray();
                                                foreach ($products as $key => $value) {
                                                    $prodArray[] = $products[$key];
                                                }
                                                return $prodArray;
                                            }
                                        });
                                        $set('dessert_products_id', function (callable $get) {
                                            $category_id = $get('categ_id');
                                            if ($category_id) {
                                                $instances = DB::table('product_category_desserts_products')->where('category_id', $category_id)->pluck('dessert_id');
                                                $prodArray = [];
                                                $products = ProductProduct::whereIn('id', $instances)->get()->pluck('id')->toArray();
                                                foreach ($products as $key => $value) {
                                                    $prodArray[] = $products[$key];
                                                }
                                                return $prodArray;
                                            }
                                        });
                                    })

                                    ->relationship('category', 'name')
                                    ->options(ProductCategory::get()->pluck('name.en', 'id'))
                                    ->translateLabel()
                                    ->required(),
                                Select::make('company_id')
                                    ->translateLabel()
                                    ->relationship('company', 'name')
                                    ->nullable(),

                                TextInput::make('list_price')

                                    ->label('Price')
                                    ->required()
                                    ->default(0)
                                    ->numeric(),
                                Toggle::make('tax_included')->default(1)
                                    ->translateLabel(),
                                Section::make('description')

                                    ->statePath('description')
                                    ->translateLabel()
                                    ->schema([
                                        Textarea::make('en'),
                                        Textarea::make('ar'),

                                    ])
                                    ->columns(2),

                            ])->columns(2),
                        Tabs\Tab::make('Attributes and Variants')
                            ->translateLabel()
                            ->schema([
                                Repeater::make('oldAttributes')

                                    ->label('Attributes')
                                    ->translateLabel()
                                    ->relationship('attributes')
                                    ->schema([
                                        Select::make('attribute_id')
                                            ->label('Name')
                                            ->translateLabel()
                                            ->options(function () {
                                                $attributes = ProductAttribute::get()->pluck('name.en', 'id');
                                                return $attributes;
                                            })
                                            ->afterStateUpdated(function (Closure $set) {
                                                $set('value', null);
                                            })
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->reactive(),
                                        Select::make('values')
                                            ->relationship('valuesOfAttributes', 'id')
                                            ->label('value')
                                            ->reactive()
                                            ->preload()
                                            ->translateLabel()
                                            ->options(function (callable $get) {
                                                $id = $get('attribute_id');
                                                $attributeValues = ProductAttributeValue::where('attribute_id', $id)->get()->pluck('name.en', 'id');
                                                return $attributeValues;
                                            })
                                            // ->suffixAction(
                                            //     Action::make('Extra Price')
                                            //         ->icon('heroicon-m-banknotes')
                                            //         ->form([
                                            //             Repeater::make('Prices')
                                            //                 ->label('Prices')
                                            //                 ->schema([
                                            //                     Select::make("attribute")
                                            //                         ->options(function (callable $get) {
                                            //                             $attribute_id = $get('attribute_id');
                                            //                         }),
                                            //                     TextInput::make('extra_price')
                                            //                         ->numeric()
                                            //                         ->required(),
                                            //                 ])->columns(2),
                                            //         ])
                                            //         ->action(function (callable $get) {
                                            //             $id = $get('attribute_id');
                                            //             dd($id);
                                            //         })
                                            // )
                                            ->searchable()
                                            ->required()
                                            ->multiple(),

                                    ])
                                    ->visibleOn('edit')

                                    ->columns(2),

                                // Toggle::make('edited'),
                                Repeater::make('attributes')
                                    ->translateLabel()
                                    ->label('New Attribute')
                                    ->schema([
                                        Select::make('attribute_id')
                                            ->label('Name')
                                            ->translateLabel()
                                            ->options(function () {
                                                $attributes = ProductAttribute::get()->pluck('name.en', 'id');
                                                return $attributes;
                                            })
                                            ->preload()
                                            ->afterStateUpdated(function (Closure $set) {
                                                $set('value', null);
                                            })
                                            ->searchable()
                                            ->required(),


                                        Select::make('value')
                                            ->translateLabel()
                                            ->options(function (callable $get) {
                                                $id = $get('attribute_id');
                                                $values = ProductAttributeValue::where('attribute_id', $id)->get()->pluck('name.en', 'id');
                                                return $values;
                                            })
                                            ->required()
                                            ->searchable()
                                            ->multiple()

                                    ])
                                    // ->deleteAction(
                                    //     fn (Action $action) => $action->requiresConfirmation(),
                                    // )
                                    ->visibleOn('create')
                                    ->defaultItems(0)
                                    ->columns(2),

                            ]),



                        Tabs\Tab::make('Kitchen')
                            ->translateLabel()
                            ->schema([
                                Select::make('kitchen_id')
                                    ->relationship('kitchen', 'name')
                                    ->nullable()
                                    ->translateLabel(),
                                Select::make('preparing_time')
                                    ->options([
                                        '0' => '',
                                        '10' => '10',
                                        '20' => '20',
                                        '30' => '30',
                                        '40' => '40',
                                        '50' => '50',
                                        '60' => '60',
                                        '70' => '70',
                                        '80' => '80',
                                        '90' => '90',
                                        '100' => '100',
                                        '110' => '110',
                                        '120' => '120',

                                    ])->translateLabel()
                                    ->nullable(),
                            ]),
                        Tabs\Tab::make('Add ons')
                            ->translateLabel()

                            ->visible(function (callable $get) {
                                $main = $get('is_combo');
                                if ($main)
                                    return false;
                                else
                                    return true;
                            })

                            ->schema([
                                Select::make('addons_id')
                                    ->label('Addons')
                                    ->translateLabel()
                                    ->relationship('productAddons',  'id')
                                    ->preload()
                                    ->options(function (callable $get) {
                                        $addons = ProductProduct::where('is_add_ons', true)->where('active', 1)->get()->pluck('name.en', 'id');
                                        return $addons;
                                    })

                                    ->multiple()
                                    ->searchable()
                                // ->visible(function (callable $get) {
                                //     $main = $get('is_combo');
                                //     if ($main)
                                //         return false;
                                //     else
                                //         return true;
                                // })
                                ,
                                Select::make('ingredients_id')
                                    ->label('Ingredients')
                                    ->translateLabel()
                                    ->relationship('productIngredients',  'id')
                                    ->options(function () {
                                        $ingredients = ProductProduct::where('is_ingredient', true)->where('active', 1)->get()->pluck('name.en', 'id');
                                        return $ingredients;
                                    })
                                    ->afterStateUpdated(function (Closure $set) {
                                        $set('removable_id', null);
                                    })
                                    ->reactive()
                                    ->multiple()
                                    ->searchable()
                                    ->preload()
                                    ->visible(function (callable $get) {
                                        $main = $get('is_combo');
                                        if ($main)
                                            return false;
                                        else
                                            return true;
                                    }),

                                Select::make('removable_id')
                                    ->translateLabel()
                                    ->label('Removable Ingredients')
                                    ->relationship('productRemovables',  'id')
                                    ->options(function (callable $get) {
                                        $selectedIngredients = $get('ingredients_id');

                                        $removableIngredients = ProductProduct::whereIn('id', $selectedIngredients)->where('active', 1)->get()->pluck('name.en', 'id');

                                        return $removableIngredients;
                                    })
                                    ->preload()
                                    ->multiple()
                                    ->searchable()
                                    ->visible(function (callable $get) {
                                        $main = $get('is_combo');
                                        if ($main)
                                            return false;
                                        else
                                            return true;
                                    }),
                            ])->columns(3),
                        Tabs\Tab::make('Content')
                            ->visible(function (callable $get) {
                                $main = $get('is_combo');
                                if ($main)
                                    return true;
                                else
                                    return false;
                            })
                            ->schema([
                                Select::make('product_id')
                                    ->label('Product Content')
                                    ->translateLabel()
                                    ->relationship('productContent',  'id')
                                    ->options(function () {
                                        $products = ProductProduct::where('is_ingredient', false)
                                            ->where('is_add_ons', false)
                                            ->where('active', 1)
                                            ->get()->pluck('name.en', 'id');
                                        return $products;
                                    })->multiple()
                                    ->searchable()
                                    ->preload()
                                // ->visible(function (callable $get) {
                                //     $main = $get('is_combo');
                                //     if ($main)
                                //         return true;
                                //     else
                                //         return false;
                                // }),
                            ]),
                        Tabs\Tab::make('Complementary Products')
                            ->translateLabel()
                            ->schema([
                                // TextInput::make('drinks_caption')

                                //     ->label('Drinks Caption')
                                //     ->default('Drinks'),
                                KeyValue::make('drinks_caption')
                                    ->translateLabel()
                                    ->schema([
                                        Textarea::make('en')
                                            ->rows(5),
                                        Textarea::make('ar')
                                            ->rows(10),
                                    ])
                                    ->valueLabel('Drinks Caption')
                                    ->translateLabel()
                                    ->keyLabel('Language')

                                    ->columnSpan(1),
                                // TextInput::make('related_caption')

                                //     ->label('Related Caption')
                                //     ->default('Related'),
                                KeyValue::make('related_caption')
                                    ->translateLabel()
                                    ->schema([
                                        Textarea::make('en')
                                            ->rows(5),
                                        Textarea::make('ar')
                                            ->rows(10),
                                    ])
                                    ->valueLabel('Related Caption')
                                    ->translateLabel()
                                    ->keyLabel('Language')

                                    ->columnSpan(1),
                                Select::make('default_drink_id')
                                    ->translateLabel()
                                    ->label('Drinks')
                                    ->preload()
                                    // ->reactive()
                                    ->relationship('productRelatedDrinks',  'product_product_id')
                                    ->options(function (callable $get) {
                                        $category_id = $get('categ_id');
                                        // if ($category_id) {

                                        // $instances = DB::table('product_category_drinks')->where('category_id', $category_id)->pluck('drink_id');
                                        // $productsRec = ProductProduct::whereIn('id', $instances)->get()->pluck('name', 'id');
                                        // $recArr=$productsRec->toArray();
                                        // $products = ProductProduct::whereNotIn('id', $instances)->get()->pluck('name', 'id');
                                        // $productsArr=$products->toArray();
                                        // return [
                                        //     'Recommended' =>
                                        //         $recArr
                                        //     ,
                                        //     'All products' => $productsArr ,
                                        // ];

                                        // }
                                        return ProductProduct::all()->pluck('name.en', 'id');
                                    })

                                    ->multiple()
                                    ->nullable(),

                                Select::make('related_products_id')->translateLabel()
                                    ->label('Related Products')
                                    ->relationship('relatedProducts',  'related_id')
                                    ->options(ProductProduct::all()->pluck('name.en', 'id'))
                                    ->preload()
                                    ->multiple()
                                    ->nullable(),

                                // TextInput::make('sides_caption')

                                //     ->label('Sides Caption')
                                //     ->default('Sides'),
                                KeyValue::make('sides_caption')
                                    ->translateLabel()
                                    ->schema([
                                        Textarea::make('en')
                                            ->rows(5),
                                        Textarea::make('ar')
                                            ->rows(10),
                                    ])
                                    ->valueLabel('Sides Caption')
                                    ->translateLabel()
                                    ->keyLabel('Language')

                                    ->columnSpan(1),
                                // TextInput::make('liked_caption')

                                //     ->label('Liked Caption')
                                //     ->default('Liked'),
                                KeyValue::make('liked_caption')
                                    ->translateLabel()
                                    ->schema([
                                        Textarea::make('en')
                                            ->rows(5),
                                        Textarea::make('ar')
                                            ->rows(10),
                                    ])
                                    ->valueLabel('Liked Caption')
                                    ->keyLabel('Language')

                                    ->columnSpan(1),

                                Select::make('sides_products_id')
                                    ->translateLabel()
                                    ->label('Sides Products')
                                    ->relationship('sideProducts',  'side_id')
                                    ->options(ProductProduct::all()->pluck('name.en', 'id'))

                                    ->preload()
                                    ->multiple()
                                    ->nullable(),

                                Select::make('liked_products_id')
                                    ->translateLabel()
                                    ->label('Liked Products')
                                    ->relationship('relatedLikedProducts',  'liked_id')
                                    ->options(ProductProduct::all()->pluck('name.en', 'id'))

                                    ->preload()
                                    ->multiple()
                                    ->nullable(),

                                // TextInput::make('desserts_caption')

                                //     ->label('Dessert Caption')
                                //     ->default('Dessert'),
                                KeyValue::make('desserts_caption')
                                    ->translateLabel()
                                    ->schema([
                                        Textarea::make('en')
                                            ->rows(5),
                                        Textarea::make('ar')
                                            ->rows(10),
                                    ])
                                    ->valueLabel('Dessert Caption')
                                    ->keyLabel('Language')

                                    ->columnSpan(1),
                                Select::make('dessert_products_id')
                                    ->translateLabel()
                                    ->label('Dessert Products')
                                    ->relationship('dessertProducts',  'dessert_id')
                                    ->options(ProductProduct::all()->pluck('name.en', 'id'))

                                    ->preload()
                                    ->multiple()
                                    ->nullable(),
                            ])->columns(2),

                    ]),


            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->translateLabel(),
                TextColumn::make('category.name.en')->label('Category')->translateLabel(),
                TextColumn::make('category.name.ar')->label('Category Arabic'),
                // TextColumn::make('name')->searchable()->translateLabel(),
                TextColumn::make('name.en')->searchable('name->en')->label('Name en'),
                TextColumn::make('name.ar')->searchable('name->ar')->label('Name ar'),
                TextColumn::make('list_price')->label('Price')->translateLabel(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('configure')
                    ->label('Configure')
                    ->url(fn (ProductTemplate $record): string => route('filament.resources.tenant/product-template-attribute-values.index', ['record' => $record])),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AttributesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductTemplates::route('/'),
            'create' => Pages\CreateProductTemplate::route('/create'),
            'edit' => Pages\EditProductTemplate::route('/{record}/edit'),
            // 'configure' => Pages\ConfigureProductTemplateAttributeValue::route('/{record}/configure')
        ];
    }

    public static function getlModelLabel(): string
    {
        return __('Products');
    }
    public static function getPluralModelLabel(): string
    {
        return __('Products');
    }
}
