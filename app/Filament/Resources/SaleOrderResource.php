<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaleOrderResource\Pages;
use App\Filament\Resources\SaleOrderResource\RelationManagers;
use App\Models\ProductProduct;
use App\Models\ResCompany;
use App\Models\ResCurrency;
use App\Models\ResPartner;
use App\Models\SaleOrder;
use App\Models\User;
use App\Models\ZoneZone;
use App\Utils\Constraints;
use App\Utils\CustomHelper;
use App\Utils\Tax;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Form;
use Filament\Resources\Table;
class SaleOrderResource extends Resource
{
    protected static ?string $model = SaleOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    public static function getNavigationGroup(): ?string
    {
        return __('Order Management');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('')->schema([

                    Hidden::make('user_id')

                        ->default(auth()->user()->id),

                    Placeholder::make('Order Number')
                        ->content(CustomHelper::getOrderName())
                        ->visibleOn('edit')
                        ->translateLabel(),
                    Placeholder::make('Order Number')
                        ->content(CustomHelper::generateOrderName())
                        ->visibleOn('create')
                        ->translateLabel(),

                    Hidden::make('company_id')
                    ->default(auth()->user()->defaultCompany->id),

                    Select::make('partner_id')
                        ->relationship('partner', 'name')
                        ->options(ResPartner::where('is_client', true)->get()->pluck('name', 'id'))
                        ->label('Customer')
                        ->searchable()
                        ->preload()
                        ->translateLabel()
                        ->required(),

                    Select::make('partner_shipping_id')
                        ->relationship('resPartnerShipping', 'name')
                        ->label('Delivery Address')
                        ->required()
                        ->options(function (callable $get) {
                            $client_id = $get('partner_id');
                            $adresses = [];
                            if ($client_id) {
                                $partner=Respartner::find($client_id);
                                $default_address=[$partner->id=>'default address'];
                                $adresses = Respartner::where('parent_id', $client_id)->get()->pluck('name', 'id')->toArray();
                                
                                $adresses=array_merge($default_address,$adresses);
                               
                            }
                            return $adresses;
                        })
                        ->searchable()
                        ->preload()
                        ->translateLabel()
                       ,
                    // Select::make('currency_id')


                    //     ->relationship('currency', 'name')
                    //     ->options(ResCurrency::all()->pluck('name.en', 'id'))
                    //     ->translateLabel()
                    //     ->required(),
                    Hidden::make('name')->default(CustomHelper::generateOrderName()),
                    // TextInput::make('state')->default('draft')->translateLabel(),
                    Select::make('zone_id')


                        ->relationship('zone', 'name')
                        ->options(ZoneZone::all()->pluck('name', 'id'))
                        ->translateLabel(),
                ])->columns(2)->translateLabel(),

                section::make('')->schema([
                    Select::make('sale_order_type_id')
                        ->relationship('saleOrderTypes', 'name')
                        ->label('Order Type')
                        ->required()
                        ->reactive()
                        ->translateLabel(),
                    Select::make('driver_id')
                        ->relationship('driver', 'name')
                        ->options(Respartner::where('is_driver', true)->get()->pluck('name', 'id'))
                        ->preload()
                        ->translateLabel()
                        ->searchable()
                        ->visible(function (callable $get) {
                            $delivery = $get('sale_order_type_id');
                            if ($delivery == "1")
                                return true;
                            else
                                return false;
                        }),
                ])->columns(2),

                Section::make("")->schema([
                    Select::make('order_status')
                        ->options([
                            2 => "Draft",
                            3 => "Confirmed",
                            4 => "In Progress",
                            5 => "Ready",
                            6 => "Out For Delivery",
                            7 => "Delivered",
                        ])
                        ->translateLabel()
                        ->default(2),
                    Textarea::make('notes')->translateLabel(),

                ]),
                Section::make('')->schema([
                    DateTimePicker::make('date_order')
                        ->label('Order Date')
                        ->translateLabel()
                        ->default(date('Y-m-d H:i:s'))

                        ->required(),
                    DateTimePicker::make('delivery_date')->label('Delivery Date')->translateLabel(),

                    Select::make('order_time_to_be_ready')
                        ->options(
                            [
                                10 => '10 mins',
                                20 => '20 mins',
                                30 => '30 mins',
                                40 => '40 mins',
                                50 => '50 mins',
                                60 => '60 mins',
                            ]
                        )
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $get, callable $set) {
                            $set('assign_time_time', date('Y-m-d H:i:s'));
                        })
                        ->translateLabel(),
                    // DateTimePicker::make('assign_time_time')

                ]),
                Section::make('')->schema([
                    Repeater::make('saleOrderLines')
                        ->itemLabel(fn (array $state): ?string => $state['product_id'] ?? null)
                        ->relationship()
                        ->translateLabel()
                        ->schema([
                            Select::make('product_id')
                            ->columnSpan(3)
                                ->relationship('product', 'name')
                                ->reactive()
                                ->translateLabel()
                                ->options(ProductProduct::where('active', 1)->get()->pluck('name.en', 'id'))->required()
                                ->afterStateUpdated(function ($state, callable $get, callable $set,$record) {
                                    $product = ProductProduct::find($state);
                                    
                                    if ($product) {
                                        $product_name = $product->name;
                                        $set('name', $product_name['en']);
                                        $set('price_unit', $product->lst_price);
                                        $set('tax', Tax::getProductProductTva($product));
                                        // Trigger calculation when product is selected
                                        $quantity = $get('product_uom_qty');
                                        if (is_numeric($quantity)) {
                                            $tax = 0;
                                            if ($get('tax')) {
                                                $tax = $get('tax');
                                            }
                                            $unit_price = $get('price_unit');
                                            if (is_numeric($unit_price) && is_numeric($tax)) {
                                                $total = $quantity * ($unit_price + $tax * $unit_price / 100);
                                                $set('price_total', round($total * $quantity, 2));
                                                $set('price_tax',round( $total - $unit_price * $quantity));
                                                $set('price_reduce_taxinc', $total / $quantity);
                                                $set('price_reduce_taxexcl', $unit_price);
                                            }
                                        }
                                        $old_price=0;
                                        $old_untaxed=0;
                                        $old_price_tax=0;
                                        if($record){
                                            $old_price=$record->price_total;
                                            $old_untaxed=$record->price_reduce_taxexcl;
                                            $old_price_tax=$record->price_tax;
                                        }
                                       
                                        $old_total=$get('../../amount_total');
                                        
                                      
                                        $set('../../amount_total',round($old_total-$old_price+$total * $quantity, 2));
                                        $old_total_untaxed=$get('../../amount_untaxed');
                                       
                                        $set('../../amount_untaxed',round($old_total_untaxed-$old_untaxed+$unit_price,2));
                                        $old_amount_tax=$get('../../amount_tax');
                                        
                                        $set('../../amount_tax', round($old_amount_tax-$old_price_tax+$total - $unit_price * $quantity,2));
                                    }
                                })->columnSpan(2),
                            Hidden::make('name'),
                            TextInput::make('notes')->label('note')->translateLabel() ->columnSpan(3),
                            TextInput::make('product_uom_qty')->numeric()->required()->Label('Quantity')
                                ->minValue(1)
                                ->default(1)
                                ->reactive()
                                ->translateLabel()
                                ->afterStateUpdated(function ($state, callable $get, callable $set,$record) {
                                    if (is_numeric($state)) {
                                        // Trigger calculation when quantity is updated
                                        $tax = 0;
                                        if ($get('tax')) {
                                            $tax = $get('tax');
                                        }
                                        $unit_price = $get('price_unit');
                                        if (is_numeric($unit_price) && is_numeric($tax)) {
                                            $total = $state * ($unit_price + $tax * $unit_price / 100);
                                            $set('price_total', round($total, 2));
                                            $set('price_tax', $total - $unit_price * $state);
                                            $set('price_reduce_taxinc', $total / $state);
                                            $set('price_reduce_taxexcl', $unit_price);
                                        }
                                    }
                                    $old_price=0;
                                    $old_untaxed=0;
                                    $old_price_tax=0;
                                    if($record){
                                        $old_price=$record->price_total;
                                        $old_untaxed=$record->price_reduce_taxexcl;
                                        $old_price_tax=$record->price_tax;
                                    }
                                    $old_total=$get('../../amount_total');
                                    $set('../../amount_total',round($old_total-$old_price+$total, 2));
                                    $old_total_untaxed=$get('../../amount_untaxed');
                                   
                                    $set('../../amount_untaxed',round($old_total_untaxed-$old_untaxed+$unit_price*$state,2));
                                    $old_amount_tax=$get('../../amount_tax');
                                    
                                    $set('../../amount_tax', round($old_amount_tax-$old_price_tax+$total - $unit_price * $state,2));
                                }),
                            Hidden::make('price_tax'),
                            Hidden::make('price_reduce_taxexcl'),
                            Hidden::make('price_reduce_taxinc'),
                            TextInput::make('price_unit')->numeric()->required()->Label('Unit Price')
                                ->minValue(0)
                                ->translateLabel()
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $get, callable $set,$record) {
                                    if (is_numeric($state)) {
                                        // Trigger calculation when unit price is updated
                                        $tax = 0;
                                        if ($get('tax')) {
                                            $tax = $get('tax');
                                        }
                                        $quantity = $get('product_uom_qty');
                                        if (is_numeric($quantity) && is_numeric($tax)) {
                                            $total = $quantity * ($state + $tax * $state / 100);
                                            $set('price_total', round($total, 2));
                                            $set('price_tax', $total - $state * $quantity);
                                            $set('price_reduce_taxinc', $total / $quantity);
                                            $set('price_reduce_taxexcl', $state);
                                        }
                                     
                                    } $old_price=0;
                                    $old_untaxed=0;
                                    $old_price_tax=0;
                                    if($record){
                                        $old_price=$record->price_total;
                                        $old_untaxed=$record->price_reduce_taxexcl;
                                        $old_price_tax=$record->price_tax;
                                    }
                                    $old_total=$get('../../amount_total');
                                    $set('../../amount_total',$old_total-$old_price+ round($total, 2));
                                    $old_total_untaxed=$get('../../amount_untaxed');
                                    
                                    $set('../../amount_untaxed',round($old_total_untaxed-$old_untaxed+$state,2));
                                    $old_amount_tax=$get('../../amount_tax');
                                   
                                    $set('../../amount_tax', round($old_amount_tax-$old_price_tax+$total - $state * $quantity,2));
                                 
                                }),
                                // TextInput::make('tax')->required()->numeric()->Label('Taxes')->translateLabel()->readOnly()->default(function (callable $get){
                                //     $tax=$get('product_id');
                                //    return $tax;
                                // }),
                                Placeholder::make('tax')
                                ->label('Tax %')
                                ->content(function (callable $get): string {
                                    $product_id=$get('product_id');
                                    $tax=0;
                                    if($product_id){
                                        $product = ProductProduct::find($product_id);
                                        $tax=Tax::getProductProductTva($product);
                                   
                                    }
                                    return $tax;
                                   
                                }),
                            TextInput::make('price_total')->numeric()->Label('Total')->translateLabel()->readOnly(),
                            Section::make('')
                                ->schema([
                                    Repeater::make('saleOrderLineImage')
                                        ->relationship('saleOrderLineImage')
                                        ->schema([
                                            FileUpload::make('image')
                                            ->openable()
                                            ->downloadable()
                                            ->imageEditor()
                                                ->image()
                                                ->disk('public')->directory('images/orderlines')
                                                ->translateLabel()
                                        ])
                                ])->hidden(function($record){
                                    if($record){
                                        return $record->saleOrderLineImage->isEmpty();
                                        
                                    } else return true;
                                }),
                        ])->reorderable(true)
                        ->columns(9)

                ]),
                Section::make('Total Amount')->schema([
                  
                    TextInput::make('amount_untaxed')->label('Untaxed Amount')->readOnly(),
                    TextInput::make('amount_tax')->label('Taxes')->readOnly(),
                    TextInput::make('amount_total')->label('Total')->readOnly()->columnSpan(2),
                ])
                    ->visibleOn('edit')
                    ->columns(2),


                // TextInput::make('partner_invoice_id'),

                // Select::make('pricelist_id')
                //     ->relationship('productPriceList','name->en')
                //     ->label('Produt Pricelist')
                //     ->required(),


                // TextInput::make('client_order_ref'),
                // TextInput::make('origin'),
                // TextInput::make('reference'),
                // TextInput::make('invoice_status'),
                // DatePicker::make('validity_date'),

                // TextInput::make('currency_rate')
                //     ->numeric(),
                // TextInput::make('amount_untaxed')
                //     ->numeric(),
                // TextInput::make('amount_tax')
                //     ->numeric(),
                // TextInput::make('amount_total')
                //     ->numeric(),
                // DateTimePicker::make('commitment_date')
                // ->default(date('Y-m-d H:i:s')),

                // Toggle::make('is_confirmed'),
                // TextInput::make('total_qty')
                //     ->numeric(),



            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
             ->defaultSort('created_at', 'desc')
            ->columns([

                TextColumn::make('name'),
                TextColumn::make('created_at')->label('Creation')->translateLabel()->date() ->sortable(),
                TextColumn::make('user.name')->label("Customer")->translateLabel(),

                TextColumn::make('order_status')->translateLabel()
                    ->formatStateUsing(function (string $state) {
                        switch ($state) {
                            case "2":
                                return "Draft";

                            case "3":
                                return "Confirmed";

                            case "4":
                                return "In Progress";

                            case "5":
                                return "Ready";
                            case "6":
                                return "Out For Delivery";
                            case "7":
                                return "Delivered";
                        }
                    }),
                TextColumn::make('order_time_to_be_ready')->label('Time To Be Ready')->translateLabel(),
                TextColumn::make('driver.name')->translateLabel(),
                TextColumn::make('resCompany.name')->label('Company')->translateLabel(),
                TextColumn::make('amount_total')->label("Total")->translateLabel(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Bill')
                ->url(fn(SaleOrder $record):string => route('get.bill', ['record' => $record->id]))
                ->openUrlInNewTab()
            ])
            ->bulkActions([
                
                   
                ]);
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
            'index' => Pages\ListSaleOrders::route('/'),
            'create' => Pages\CreateSaleOrder::route('/create'),
            'edit' => Pages\EditSaleOrder::route('/{record}/edit'),
        ];
    }

    public static function getlModelLabel(): string
    {
        return __('Sale Order');
    }
    public static function getPluralModelLabel(): string
    {
        return __('Sale Order');
    }
    
}
