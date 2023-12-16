<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CareerInformationResource\Pages;
use App\Filament\Resources\CareerInformationResource\RelationManagers;
use App\Models\Tenant\CareerInformation;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Resources\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CareerInformationResource extends Resource
{
    protected static ?string $model = CareerInformation::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    public static function getNavigationGroup(): ?string
    {
        return __('Recruitment');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                Select::make('company_id')
                    ->relationship('company', 'name')
                    ->required(),
                Textarea::make('description'),
                TextInput::make('title1'),
                FileUpload::make('icon1')
                    ->disk('public')->directory('images/CareerInfoIcon1')
                    ->image(),
                // ->imageEditor()
                // ->imageEditorAspectRatios([
                //     '16:9',
                //     '4:3',
                //     '1:1',
                // ]),
                Textarea::make('description1'),
                TextInput::make('title2'),
                FileUpload::make('icon2')
                    ->disk('public')->directory('images/CareerInfoIcon2')
                    ->image(),
                // ->imageEditor()
                // ->imageEditorAspectRatios([
                //     '16:9',
                //     '4:3',
                //     '1:1',
                // ]),
                Textarea::make('description2'),
                TextInput::make('title3'),
                FileUpload::make('icon3')
                    ->disk('public')->directory('images/CareerInfoIcon3')
                    ->image(),
                // ->imageEditor()
                // ->imageEditorAspectRatios([
                //     '16:9',
                //     '4:3',
                //     '1:1',
                // ]),
                Textarea::make('description3'),
                TextInput::make('vacancies_title'),
                TextInput::make('vacancies_description'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListCareerInformation::route('/'),
            'create' => Pages\CreateCareerInformation::route('/create'),
            'edit' => Pages\EditCareerInformation::route('/{record}/edit'),
        ];
    }
}
