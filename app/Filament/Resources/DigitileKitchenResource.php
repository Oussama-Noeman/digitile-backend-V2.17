<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DigitileKitchenResource\Pages;
use App\Filament\Resources\DigitileKitchenResource\RelationManagers;
use App\Models\Tenant\DigitileKitchen;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Resources\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DigitileKitchenResource extends Resource
{
    protected static ?string $model = DigitileKitchen::class;
    public static function getNavigationGroup(): ?string
    {
        return __('Product Management');
    }

    protected static ?string $navigationIcon = 'heroicon-o-library';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Select::make('company_id')
                    ->relationship('company', 'name')
                    ->required(),
                Toggle::make('is_default'),
                // Forms\Components\TextInput::make('state')
                // ->required(),

                FileUpload::make('image')
                    ->disk('public')->directory('images/DigitileKitchen')
                    ->image()
                    // ->imageEditor()
                    // ->imageEditorAspectRatios([
                    //     '16:9',
                    //     '4:3',
                    //     '1:1',
                    // ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('name'),
                ImageColumn::make('image'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
        // ->bulkActions([
        //     Tables\Actions\BulkActionGroup::make([
        //         Tables\Actions\DeleteBulkAction::make(),
        //     ]),
        // ])
        // ->emptyStateActions([
        //     Tables\Actions\CreateAction::make(),
        // ]);
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
            'index' => Pages\ListDigitileKitchens::route('/'),
            'create' => Pages\CreateDigitileKitchen::route('/create'),
            'edit' => Pages\EditDigitileKitchen::route('/{record}/edit'),
        ];
    }

    public static function getlModelLabel(): string
    {
        return __('Kitchen');
    }
    public static function getPluralModelLabel(): string
    {
        return __('Kitchen');
    }
}
