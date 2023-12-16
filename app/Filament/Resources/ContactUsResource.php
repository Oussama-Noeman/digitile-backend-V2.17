<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactUsResource\Pages;
use App\Filament\Resources\ContactUsResource\RelationManagers;
use App\Models\Tenant\ContactUs;
use App\Models\Tenant\ResCompany;
use App\Utils\Constraints;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactUsResource extends Resource
{
    protected static ?string $model = ContactUs::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    public static function getNavigationGroup(): ?string
    {
        return __('Help & Support Section');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->translateLabel(),
                TextInput::make('email')->translateLabel(),
                TextInput::make('phone')->translateLabel(),
                TextInput::make('comment')->translateLabel(),
                Select::make('company_id')->translateLabel()
                    ->relationship('company', 'name'),
                // ->options(Constraints::allowedCompanies()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->translateLabel(),
                TextColumn::make('email')->translateLabel(),
                TextColumn::make('phone')->translateLabel(),
                TextColumn::make('company_id')
                    ->formatStateUsing(function (string $state) {
                        $name = ResCompany::where('id', $state)->pluck('name')[0];
                        return $name;
                    })
                    ->translateLabel(),
                TextColumn::make('comment')->translateLabel(),




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
            'index' => Pages\ListContactUs::route('/'),
            'create' => Pages\CreateContactUs::route('/create'),
            'edit' => Pages\EditContactUs::route('/{record}/edit'),
        ];
    }

    public static function getlModelLabel(): string
    {
        return __('Contact Us');
    }
    public static function getPluralModelLabel(): string
    {
        return __('Contact Us');
    }
}
