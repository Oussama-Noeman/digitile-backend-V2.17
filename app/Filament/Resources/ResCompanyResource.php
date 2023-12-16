<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResCompanyResource\Pages;
use App\Filament\Resources\ResCompanyResource\RelationManagers;
use App\Models\ResCompany;
use App\Models\Tenant\ResCurrency;
use App\Models\ResPartner;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Form;
use Filament\Resources\Table;
class ResCompanyResource extends Resource
{
    protected static ?string $model = ResCompany::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    public static function getNavigationGroup(): ?string
    {
        return __('System Settings');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->schema([
                        TextInput::make('name')->translateLabel()->required(),

                        FileUpload::make('image')
                            ->disk('public')->directory('images/Company')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                                '1:1',
                            ])->translateLabel()->required(),
                    ])->columns(2),

                Tabs::make('Heading')
                    ->tabs([
                        Tabs\Tab::make('General Information')
                            ->schema([
                                Select::make('partner_id')->relationship('resPartner', 'name')
                                    ->label('Contact')
                                    ->options(ResPartner::all()->pluck('name', 'id'))
                                    ->translateLabel()->required(),
                                TextInput::make('email')->translateLabel()->nullable(),
                                TextInput::make('phone')->translateLabel()->nullable(),
                                TextInput::make('mobile')->translateLabel()->nullable(),

                                Select::make('currency_id')
                                    ->relationship('resCurrency', 'name')
                                    ->options(ResCurrency::all()->pluck('name.en', 'id'))
                                    ->Label('Currency')
                                    ->translateLabel()->required(),

                                Select::make('parent_id')->relationship('parent', 'name')
                                    ->label("Parent Company")
                                    ->options(ResCompany::where('is_main', true)->get()->pluck('name', 'id'))
                                    ->visible(function (callable $get) {
                                        $main = $get('is_main');
                                        if (!$main)
                                            return true;
                                        else
                                            return false;
                                    })->translateLabel(),

                                Section::make('SOCIAL MEDIA')
                                    ->schema([
                                        TextInput::make('whatsapp')->translateLabel()->nullable(),
                                        TextInput::make('social_twitter')->translateLabel()->label('Twitter')->nullable(),
                                        TextInput::make('social_facebook')->translateLabel()->label('Facebook')->nullable(),
                                        TextInput::make('social_github')->translateLabel()->label('Github')->nullable(),
                                        TextInput::make('social_linkedin')->translateLabel()->label('Linkendin')->nullable(),
                                        TextInput::make('social_youtube')->translateLabel()->label('Youtube')->nullable(),
                                        TextInput::make('social_instagram')->translateLabel()->label('Instagram')->nullable(),
                                    ])->columnSpan(1)->columns(1),
                                Section::make('INFORMATION')
                                    ->schema([
                                        Textarea::make('terms_and_conditions')->translateLabel()->nullable()->rows(5),
                                        Textarea::make('privacy_policy')->translateLabel()->nullable()->rows(5),
                                        Textarea::make('support')->translateLabel()->nullable()->rows(5),
                                        TextInput::make('tax')->translateLabel()->label('tax %')->numeric()->default(11),
                                        Toggle::make('tax_included')->translateLabel()->default(1),
                                    ])->columnSpan(1),

                            ])->columns(2),

                        Tabs\Tab::make('Address')
                            ->schema([
                                TextInput::make('city')->translateLabel()->nullable(),
                                TextInput::make('area')->translateLabel()->nullable(),
                                TextInput::make('street')->translateLabel()->nullable(),
                                TextInput::make('near')->translateLabel()->nullable(),
                                TextInput::make('bulding')->translateLabel()->nullable(),
                                TextInput::make('floor')->translateLabel()->nullable()->numeric(),

                            ])->columns(2),

                        Tabs\Tab::make('Delivery Fees')
                            ->schema([
                                Radio::make('fees_type')->translateLabel()->nullable()
                                    ->options([
                                        '0' => 'Fixed',
                                        '1' => 'By Zone'
                                    ])
                                    ->reactive()
                                    ->default(0)
                                    ->nullable(),
                                Section::make('')
                                    ->visible(function (callable $get) {
                                        $main = $get('fees_type');
                                        if ($main || $main == "0")
                                            return true;
                                        else
                                            return false;
                                    })
                                    ->schema([
                                        TextInput::make('fixed_fees')
                                            ->numeric()
                                            ->translateLabel()->nullable()
                                            ->visible(function (callable $get) {
                                                $main = $get('fees_type');
                                                if ($main == "0")
                                                    return true;
                                                else
                                                    return false;
                                            }),


                                        Textarea::make('To determine the delivery fee of the zones, kindly provide the cost for each area within the zone modal.')
                                            ->disabled()
                                            ->placeholder('To determine the delivery fee of the zones, kindly provide the cost for each area within the zone modal.')
                                            // ->hint('To determine the delivery fee of the zones, kindly provide the cost for each area within the zone modal.')
                                            ->translateLabel()->visible(function (callable $get) {
                                                $main = $get('fees_type');
                                                if ($main == "1")
                                                    return true;
                                                else
                                                    return false;
                                            }),
                                    ])->columnSpan(1)
                            ])->columns(2),
                        Tabs\Tab::make('Delivery Time')
                            ->schema([
                                Radio::make('delivery_time_type')->translateLabel()->nullable()
                                    ->options([
                                        '0' => 'Fixed',
                                        '1' => 'By Zone'
                                    ])
                                    ->reactive()
                                    ->default(0)
                                    ->nullable(),
                                Section::make('')
                                    ->visible(function (callable $get) {
                                        $main = $get('delivery_time_type');
                                        if ($main || $main == "0")
                                            return true;
                                        else
                                            return false;
                                    })
                                    ->schema([
                                        TextInput::make('fixed_time')
                                            ->numeric()
                                            ->translateLabel()->nullable()
                                            ->visible(function (callable $get) {
                                                $main = $get('delivery_time_type');
                                                if ($main == "0")
                                                    return true;
                                                else
                                                    return false;
                                            }),

                                        Textarea::make('To determine the delivery fee of the zones, kindly provide the cost for each area within the zone modal.')
                                            ->disabled()
                                            ->placeholder('To determine the delivery fee of the zones, kindly provide the cost for each area within the zone modal.')
                                            // ->hint('To determine the delivery fee of the zones, kindly provide the cost for each area within the zone modal.')
                                            ->translateLabel()->visible(function (callable $get) {
                                                $main = $get('delivery_time_type');
                                                if ($main == "1")
                                                    return true;
                                                else
                                                    return false;
                                            }),
                                    ])->columnSpan(1)
                            ])->columns(2),
                        Tabs\Tab::make('Category')
                            ->schema([
                                TextInput::make('category_title')
                                    ->translateLabel()->nullable(),
                                FileUpload::make('category_image_attachment')
                                    ->disk('public')->directory('images/Company')
                                    ->image()
                                    ->imageEditor()
                                    ->imageEditorAspectRatios([
                                        '16:9',
                                        '4:3',
                                        '1:1',
                                    ]),
                            ]),
                        Tabs\Tab::make('Cart')
                            ->schema([
                                TextInput::make('cart_title')
                                    ->translateLabel()->nullable(),
                                FileUpload::make('cart_image_attachment')
                                    ->disk('public')->directory('images/Company')
                                    ->image()
                                    ->imageEditor()
                                    ->imageEditorAspectRatios([
                                        '16:9',
                                        '4:3',
                                        '1:1',
                                    ]),
                            ]),
                        Tabs\Tab::make('Checkout')
                            ->schema([
                                TextInput::make('checkout_title')
                                    ->translateLabel()->nullable(),
                                FileUpload::make('checkout_image_attachment')
                                    ->disk('public')->directory('images/Company')
                                    ->image()
                                    ->imageEditor()
                                    ->imageEditorAspectRatios([
                                        '16:9',
                                        '4:3',
                                        '1:1',
                                    ]),
                            ]),
                        Tabs\Tab::make('Deal')
                            ->schema([
                                TextInput::make('deal_title1')
                                    ->translateLabel()->nullable(),
                                TextInput::make('deal_title2')
                                    ->translateLabel()->nullable(),
                                FileUpload::make('deal_banner_image_attachment')
                                    ->disk('public')->directory('images/Company')
                                    ->image()
                                    ->imageEditor()
                                    ->imageEditorAspectRatios([
                                        '16:9',
                                        '4:3',
                                        '1:1',
                                    ]),
                                FileUpload::make('deal_background_image_attachment')
                                    ->disk('public')->directory('images/Company')
                                    ->image()
                                    ->imageEditor()
                                    ->imageEditorAspectRatios([
                                        '16:9',
                                        '4:3',
                                        '1:1',
                                    ]),
                            ]),
                        Tabs\Tab::make('Sign in/Up Banner')
                            ->schema([
                                FileUpload::make('sign_banner_attachment')
                                    ->disk('public')->directory('images/Company')
                                    ->image()
                                    ->imageEditor()
                                    ->imageEditorAspectRatios([
                                        '16:9',
                                        '4:3',
                                        '1:1',
                                    ]),
                            ]),
                            Tabs\Tab::make('Delivery/Pickup')
                            ->schema([
                               Toggle::make('has_pickup'),
                               Toggle::make('has_delivery'),

                            ]),
                    ]),
                FileUpload::make('faq_banner')
                    ->disk('public')->directory('images/faq_banner')
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '16:9',
                        '4:3',
                        '1:1',
                    ])->translateLabel(),
                FileUpload::make('career_banner')
                    ->disk('public')->directory('images/career_banner')
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '16:9',
                        '4:3',
                        '1:1',
                    ])->translateLabel(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Company Name'))
                    ->sortable()->searchable(),
                TextColumn::make('resPartner.name')
                    ->label(__('partner'))
                    ->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListResCompanies::route('/'),
            'create' => Pages\CreateResCompany::route('/create'),
            'edit' => Pages\EditResCompany::route('/{record}/edit'),
        ];
    }

    public static function getlModelLabel(): string
    {
        return __('Companies');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Companies');
    }
}
