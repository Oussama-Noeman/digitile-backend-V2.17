<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DriverChatResource\Pages;
use App\Filament\Resources\DriverChatResource\RelationManagers;
use App\Models\Tenant\DriverChat;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DriverChatResource extends Resource
{
    public static function canViewAny(): bool
    {
        return true; // b el project yalli 2abel kenet false
    }
    protected static ?string $model = DriverChat::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function getNavigationGroup(): ?string
    {
        return __('Delivery');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->schema([
                        TextInput::make('order_id'),
                        TextInput::make('message'),
                        TextInput::make('driver_user_id'),
                        TextInput::make('client_user_id'),
                    ])->columns(2),
                Repeater::make('image')
                    ->relationship('image')
                    ->schema([
                        FileUpload::make('image_attachment')
                            ->image()
                            ->disk('public')->directory('images/driverline')

                    ])


            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_id')->label('Sale Order'),
                TextColumn::make('message')->label('Message'),
                TextColumn::make('driver_user_id')->label('Driver'),
                TextColumn::make('client_user_id')->label('Client'),
                TextColumn::make('created_at')->label('Created On'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListDriverChats::route('/'),
            'create' => Pages\CreateDriverChat::route('/create'),
            // 'edit' => Pages\EditDriverChat::route('/{record}/edit'),
        ];
    }

    public static function getlModelLabel(): string
    {
        return __('Driver Chat');
    }
    public static function getPluralModelLabel(): string
    {
        return __('Driver Chat');
    }
}
