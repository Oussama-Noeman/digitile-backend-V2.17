<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResLangResource\Pages;
use App\Filament\Resources\ResLangResource\RelationManagers;
use App\Models\Tenant\ResLang;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Form;
use Filament\Resources\Table;

class ResLangResource extends Resource
{
    public static function canViewAny(): bool
    {
        return true;
    }
    protected static ?string $model = ResLang::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationGroup(): ?string
    {
        return __('Res');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required()->translateLabel(),
                TextInput::make('code')->required()->translateLabel(),
                TextInput::make('iso_code')->translateLabel(),
                TextInput::make('url_code')->required()->translateLabel(),
                TextInput::make('direction')->required()->translateLabel(),
                TextInput::make('date_format')->required()->translateLabel(),
                TextInput::make('time_format')->required()->translateLabel(),
                TextInput::make('week_start')->required()->translateLabel(),
                TextInput::make('grouping')->required()->translateLabel(),
                TextInput::make('decimal_point')->required()->translateLabel(),
                TextInput::make('thousands_sep')->translateLabel(),
                Toggle::make('active')->translateLabel(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->translateLabel(),
                TextColumn::make('code')->translateLabel(),
                TextColumn::make('iso_code')->translateLabel(),
                TextColumn::make('url_code')->translateLabel(),
                TextColumn::make('direction')->translateLabel(),
                TextColumn::make('date_format')->translateLabel(),
                TextColumn::make('time_format')->translateLabel(),
                TextColumn::make('week_start')->translateLabel(),
                TextColumn::make('grouping')->translateLabel(),
                TextColumn::make('decimal_point')->translateLabel(),
                TextColumn::make('thousands_sep')->translateLabel(),
                TextColumn::make('active')->translateLabel(),
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
            'index' => Pages\ListResLangs::route('/'),
            'create' => Pages\CreateResLang::route('/create'),
            'edit' => Pages\EditResLang::route('/{record}/edit'),
        ];
    }

    public static function getlModelLabel(): string
    {
        return __('Lang');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Lang');
    }
}
