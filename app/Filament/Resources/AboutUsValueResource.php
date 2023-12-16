<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AboutUsValueResource\Pages;
use App\Filament\Resources\AboutUsValueResource\RelationManagers;
use App\Models\Tenant\AboutUsValue;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Resources\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AboutUsValueResource extends Resource
{
    protected static ?string $model = AboutUsValue::class;

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';
    public static function getNavigationGroup(): ?string
    {
        return __('Website Settings');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('company_id')
                    ->relationship('company', 'name')
                    ->nullable(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('about_us_value_image_attachment')
                    ->image(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('company.name')
                    // ->numeric()
                    ->sortable(),
                //                Tables\Columns\ImageColumn::make('about_us_value_image_attachment'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListAboutUsValues::route('/'),
            'create' => Pages\CreateAboutUsValue::route('/create'),
            'view' => Pages\ViewAboutUsValue::route('/{record}'),
            'edit' => Pages\EditAboutUsValue::route('/{record}/edit'),
        ];
    }

    public static function getlModelLabel(): string
    {
        return __('Value');
    }
    public static function getPluralModelLabel(): string
    {
        return __('Value');
    }
}
