<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FirstOrderResource\Pages;
use App\Filament\Resources\FirstOrderResource\RelationManagers;
use App\Models\Tenant\FirstOrder;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Resources\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FirstOrderResource extends Resource
{

    protected static ?string $model = FirstOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-in';
    public static function getNavigationGroup(): ?string
    {
        return __('Promotion Management');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Radio::make("type")
                    ->options([
                        '1' => "Discount Percentage",
                        '2' => "Discount Amount",
                        '3' => "Free Delivery"
                    ])

                    ->reactive(),
                TextInput::make('amount')
                    ->numeric()
                    ->required()
                    ->reactive()
                    ->visible(function (callable $get) {

                        $main = $get('type');

                        if ($main == '1' ||  $main == '2') {

                            return true;
                        } else {

                            return false;
                        }
                    }),
                Toggle::make('active'),
            ]);
    }

    public static function table(Table $table): Table
    {
        // $record = $_GET['record'] ?? null;
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->description(""),
                Tables\Columns\TextColumn::make('amount'),
                Tables\Columns\ToggleColumn::make('active'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
        // ->bulkActions([
        //     Tables\Actions\BulkActionGroup::make([
        //         // Tables\Actions\DeleteBulkAction::make(),
        //     ]),
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
            'index' => Pages\ListFirstOrders::route('/'),
            // 'create' => Pages\CreateFirstOrder::route('/create'),
            'edit' => Pages\EditFirstOrder::route('/{record}/edit'),
        ];
    }
    public static function getlModelLabel(): string
    {
        return __('First Order');
    }
    public static function getPluralModelLabel(): string
    {
        return __('First Order');
    }
}
