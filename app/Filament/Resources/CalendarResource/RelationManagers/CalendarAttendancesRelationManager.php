<?php

namespace App\Filament\Resources\CalendarResource\RelationManagers;


use Closure;
use DateTime;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Resources\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CalendarAttendancesRelationManager extends RelationManager
{
    protected static string $relationship = 'calendarAttendances';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('name'),
                Select::make('dayofweek')
                    ->options([
                        '0' => 'Monday',
                        '1' => 'Tuesday',
                        '2' => 'Wednesday',
                        '3' => 'Thursday',
                        '4' => 'Friday',
                        '5' => 'Saturday',
                        '6' => 'Sunday'
                    ])
                    ->label('Day of week')
                    ->required()
                    ->afterStateUpdated(function (callable $set, $state) {

                        switch ($state) {
                            case '0':
                                $name = "Monday";
                                break;

                            case '1':
                                $name = "Tuesday";
                                break;
                            case '2':
                                $name = "Wednesday";
                                break;

                            case '3':
                                $name = "Thursday";
                                break;

                            case '4':
                                $name = "Friday";
                                break;
                            case '5':
                                $name = "Saturday";
                                break;
                            case '6':
                                $name = "Sunday";
                                break;
                        }
                        $set('name', $name);
                    }),
                // Select::make('day_period')
                //     ->options([
                //         'Morning' => 'Morning',
                //         'Afternoon' => 'Afternoon',
                //     ])
                //     ->label('Day Period')
                //     ->required(),
                TimePicker::make('hour_from')
                    ->reactive()
                    ->required(),
                TimePicker::make('hour_to')
                    ->minDate(function (callable $get) {
                        return $get('hour_from');
                    })
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('dayofweek')
                    ->formatStateUsing(function (string $state) {
                        switch ($state) {
                            case "0":
                                return "Monday";

                            case "1":
                                return "Tuesday";

                            case "2":
                                return "Wednesday";

                            case "3":
                                return "Thursday";
                            case "4":
                                return "Friday";
                            case "5":
                                return "Saturday";
                            case "6":
                                return "Sunday";
                        }
                    }),
                Tables\Columns\TextColumn::make('day_period'),
                Tables\Columns\TextColumn::make('hour_from'),
                Tables\Columns\TextColumn::make('hour_to'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
}
