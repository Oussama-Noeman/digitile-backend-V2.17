<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MainPageSectionResource\Pages;
use App\Filament\Resources\MainPageSectionResource\RelationManagers;
use App\Models\Tenant\MainPageSection;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Form;
use Filament\Resources\Table;

class MainPageSectionResource extends Resource
{
    protected static ?string $model = MainPageSection::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    public static function getNavigationGroup(): ?string
    {
        return __('Website Settings');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('company_id')
                    ->relationship('company', 'name')
                    ->label('Company')
                    ->required(),
                Select::make('section_number')
                    ->options([
                        "1" => "1",
                        "2" => "2",
                        "3" => "3",
                    ])->required(),
                KeyValue::make('name')
                    ->schema([
                        TextInput::make('en'),
                        TextInput::make('ar'),

                    ])
                    ->required(),
                FileUpload::make('image')
                    ->disk('public')->directory('images/MainPageSection')
                    ->image()
                    // ->imageEditor()
                    // ->imageEditorAspectRatios([
                    //     '16:9',
                    //     '4:3',
                    //     '1:1',
                    // ])
                    ->required(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('section_number'),
                TextColumn::make('name'),
                ImageColumn::make('image'),
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
            RelationManagers\ProductsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMainPageSections::route('/'),
            'create' => Pages\CreateMainPageSection::route('/create'),
            'edit' => Pages\EditMainPageSection::route('/{record}/edit'),
        ];
    }
    public static function getlModelLabel(): string
    {
        return __('Main Page Section');
    }
    public static function getPluralModelLabel(): string
    {
        return __('Main Page Section');
    }
}
