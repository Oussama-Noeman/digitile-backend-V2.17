<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MainBanner3Resource\Pages;
use App\Filament\Resources\MainBanner3Resource\RelationManagers;
use App\Models\Tenant\MainBanner3;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MainBanner3Resource extends Resource
{
    protected static ?string $model = MainBanner3::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    public static function getNavigationGroup(): ?string
    {
        return __('Website Settings');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->schema([
                        Select::make('company_id')
                            ->relationship('company', 'name')
                            ->label('Company')
                            ->required(),
                        TextInput::make('name')
                            ->required(),
                        Textarea::make('description'),
                        TextInput::make('banner_url'),
                    ])->columnSpan(2)->columns(1),
                Section::make('')
                    ->schema([
                        FileUpload::make('image')
                            ->disk('public')->directory('images/MainBanner3')
                            ->required()
                            ->image()
                        // ->imageEditor()
                        // ->imageEditorAspectRatios([
                        //     '16:9',
                        //     '4:3',
                        //     '1:1',
                        // ])
                    ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('description'),
                TextColumn::make('banner_url'),
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
            'index' => Pages\ListMainBanner3s::route('/'),
            'create' => Pages\CreateMainBanner3::route('/create'),
            'edit' => Pages\EditMainBanner3::route('/{record}/edit'),
        ];
    }

    public static function getlModelLabel(): string
    {
        return __('Main Banner 3');
    }
    public static function getPluralModelLabel(): string
    {
        return __('Main Banner 3');
    }
}
