<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MailingContactResource\Pages;
use App\Filament\Resources\MailingContactResource\RelationManagers;
use App\Models\Tenant\MailingContact;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Resources\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MailingContactResource extends Resource
{
    protected static ?string $model = MailingContact::class;

    protected static ?string $navigationIcon = 'heroicon-o-mail';
    public static function getNavigationGroup(): ?string
    {
        return __('Help & Support Section');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
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
            'index' => Pages\ListMailingContacts::route('/'),
            'create' => Pages\CreateMailingContact::route('/create'),
            'edit' => Pages\EditMailingContact::route('/{record}/edit'),
        ];
    }

    public static function getlModelLabel(): string
    {
        return __('Mailing Contact');
    }
    public static function getPluralModelLabel(): string
    {
        return __('Mailing Contact');
    }
}
