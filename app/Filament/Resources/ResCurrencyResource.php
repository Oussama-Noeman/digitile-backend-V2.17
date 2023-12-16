<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResCurrencyResource\Pages;
use App\Filament\Resources\ResCurrencyResource\RelationManagers;
use App\Models\Tenant\ResCurrency;
use Filament\Forms;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Textarea;
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
class ResCurrencyResource extends Resource
{
    protected static ?string $model = ResCurrency::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function getNavigationGroup(): ?string
    {
        return __('System Settings');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                KeyValue::make('name')
                    ->schema([
                        Textarea::make('en'),
                        Textarea::make('ar'),
                    ])
                    ->translateLabel()
                    ->valueLabel(__('Name'))
                    ->editableKeys(false)
                    ->keyLabel(__('Language'))
                    ->deletable(false)
                    ->addable(false)
                    ->columnSpan(1)
                    ->required(),
                KeyValue::make('symbol')
                    ->schema([
                        Textarea::make('en'),
                        Textarea::make('ar'),
                    ])
                    ->translateLabel()
                    ->valueLabel(__('Name'))
                    ->editableKeys(false)
                    ->keyLabel(__('Language'))
                    ->deletable(false)
                    ->addable(false)
                    ->columnSpan(1)
                    ->required(),

                TextInput::make('decimal_places')->numeric()->nullable()->translateLabel(),
                TextInput::make('full_name')->nullable()->translateLabel(),
                TextInput::make('position')->nullable()->translateLabel(),
                TextInput::make('currency_unit_label')->nullable()->translateLabel(),
                TextInput::make('currency_subunit_label')->nullable()->translateLabel(),
                TextInput::make('rounding')->numeric()->nullable()->translateLabel(),
                Toggle::make('active')->nullable()->translateLabel(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('symbol.en')->label(__('Currency'))->sortable()->translateLabel(),
                TextColumn::make('symbol.ar')->label(__('Symbol'))->sortable()->translateLabel(),
                TextColumn::make('name.en')->label(__('Name'))->sortable()->translateLabel(),
                TextColumn::make('updated_at')->label(__('Last Update'))->sortable()->translateLabel(),
                TextColumn::make('symbol.en')->label(__('Currency'))->sortable()->translateLabel(),

                ToggleColumn::make('active')->label(__('Active'))->sortable()->translateLabel(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListResCurrencies::route('/'),
            'create' => Pages\CreateResCurrency::route('/create'),
            'edit' => Pages\EditResCurrency::route('/{record}/edit'),
        ];
    }

    public static function getlModelLabel(): string
    {
        return __('Currencies');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Currencies');
    }
}
