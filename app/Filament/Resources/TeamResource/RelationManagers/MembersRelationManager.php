<?php

namespace App\Filament\Resources\TeamResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Resources\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MembersRelationManager extends RelationManager
{
    protected static string $relationship = 'members';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('fname')
                    ->Label('First Name')
                    ->translateLabel()
                    ->required(),
                TextInput::make('lname')
                    ->Label('Last Name')
                    ->translateLabel()
                    ->required(),
                FileUpload::make('member_image_attachment')
                    ->disk('public')->directory('images/Member')
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '16:9',
                        '4:3',
                        '1:1',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // ->recordTitleAttribute('fname')
            ->columns([
                Tables\Columns\TextColumn::make('fname')
                    ->label("Fisrt Name"),
                Tables\Columns\TextColumn::make('lname')
                    ->label("Last Name"),
                ImageColumn::make('member_image_attachment'),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()

            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                // Tables\Actions\EditAction::make(),

            ]);
        // ->bulkActions([
        //     Tables\Actions\BulkActionGroup::make([
        //         // ...
        //         Tables\Actions\DetachBulkAction::make(),
        //     ]),
        //     // Tables\Actions\BulkActionGroup::make([
        //     //     Tables\Actions\DeleteBulkAction::make(),
        //     // ]),
        // ])
        // ->emptyStateActions([
        //     Tables\Actions\CreateAction::make()

        //     // Tables\Actions\CreateAction::make(),
        // ]);
    }
}
