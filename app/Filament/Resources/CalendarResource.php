<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CalendarResource\Pages;
use App\Filament\Resources\CalendarResource\RelationManagers;
use App\Models\Calendar;
use App\Models\ResCompany;
use App\Models\Tenant\ResourceCalendar;
use App\Utils\Constraints;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CalendarResource extends Resource
{
    protected static ?string $model = ResourceCalendar::class;

    // protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    public static function getNavigationGroup(): ?string
    {
        return __('System Settings');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->translateLabel()
                    ->required(),
                Select::make('company_id')
                    ->label('Company')
                    ->translateLabel()
                    ->options(Constraints::allowedCompanies())
                    ->relationship('company', 'name')
                    ->required(),
                // TextInput::make('hours_per_day')
                // ->translateLabel()
                //     ->label('Average Hour per Day')
                //     ->required(),
                // TextInput::make('tz')
                // ->translateLabel()
                //     ->label('Timezone')
                //     ->required(),
                Toggle::make('active')
                    ->translateLabel()
                    ->default(true),
                // Toggle::make('is_working_day')
                // ->translateLabel()
                //     ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('company.name')->label('Company'),
                TextColumn::make('hours_per_day')->label('hours/day'),
                TextColumn::make('tz')->label('Time zone'),
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
            RelationManagers\CalendarAttendancesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCalendars::route('/'),
            'create' => Pages\CreateCalendar::route('/create'),
            'edit' => Pages\EditCalendar::route('/{record}/edit'),
        ];
    }

    public static function getlModelLabel(): string
    {
        return __('Working Times');
    }
    public static function getPluralModelLabel(): string
    {
        return __('Working Times');
    }
}
