<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HrApplicationResource\Pages;
use App\Filament\Resources\HrApplicationResource\RelationManagers;
use App\Models\Tenant\HrApplication;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HrApplicationResource extends Resource
{
    protected static ?string $model = HrApplication::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';
    public static function getNavigationGroup(): ?string
    {
        return __('Recruitment');
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
                TextColumn::make("job_id"),
                TextColumn::make("name"),
                TextColumn::make("email_from"),
                TextColumn::make("partner_name"),
                TextColumn::make("description"),
                TextColumn::make("file"),
                TextColumn::make("partner_mobile"),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ]);
        // ->bulkActions([
        //     // Tables\Actions\BulkActionGroup::make([
        //     //     Tables\Actions\DeleteBulkAction::make(),
        //     // ]),
        // ])
        // ->emptyStateActions([
        //     // Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListHrApplications::route('/'),
            'create' => Pages\CreateHrApplication::route('/create'),
            // 'edit' => Pages\EditHrApplication::route('/{record}/edit'),
        ];
    }
    public static function getlModelLabel(): string
    {
        return __('Applicantions');
    }
    public static function getPluralModelLabel(): string
    {
        return __('Applicantions');
    }
}
