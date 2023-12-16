<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResGroupResource\Pages;
use App\Filament\Resources\ResGroupResource\RelationManagers;
use App\Models\ResGroup;
use Filament\Forms;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Form;
use Filament\Resources\Table;
class ResGroupResource extends Resource
{
    public static function canViewAny(): bool
    {
        return false;
    }
    protected static ?string $model = ResGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationGroup(): ?string
    {
        return __('Res');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                KeyValue::make('name')
                    ->schema([
                        TextInput::make('en'),
                        TextInput::make('ar'),
                    ])
                    ->addable(false)
                    ->deletable(false)
                    ->editableKeys(false)
                    ->required()
                    ->translateLabel(),
                KeyValue::make('comment')
                    ->schema([
                        TextInput::make('en'),
                        TextInput::make('ar'),
                    ])
                    ->addable(false)
                    ->deletable(false)
                    ->editableKeys(false)
                    ->translateLabel(),
                Toggle::make('share')->translateLabel(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->translateLabel(),
                TextColumn::make('comment')->translateLabel(),
                TextColumn::make('share')->translateLabel(),
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
            'index' => Pages\ListResGroups::route('/'),
            'create' => Pages\CreateResGroup::route('/create'),
            'edit' => Pages\EditResGroup::route('/{record}/edit'),
        ];
    }

    public static function getlModelLabel(): string
    {
        return __('Groups');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Groups');
    }
}
