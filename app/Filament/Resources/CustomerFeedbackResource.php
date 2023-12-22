<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerFeedbackResource\Pages;
use App\Filament\Resources\CustomerFeedbackResource\RelationManagers;
use App\Models\Tenant\CustomerFeedback;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Resources\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerFeedbackResource extends Resource
{
    protected static ?string $model = CustomerFeedback::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-alt';
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
                    ->translateLabel()
                    ->nullable(),
                Forms\Components\TextInput::make('name')
                    ->required()->translateLabel()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('customer_feedback_image_attachment')
                    ->image()->Label('Image')->translateLabel(),
                Forms\Components\TextInput::make('customer_comment')->Label('Comment')->translateLabel(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_id')->translateLabel()
                    // ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')->translateLabel()
                    ->searchable(),
                Tables\Columns\ImageColumn::make('customer_feedback_image_attachment')->translateLabel(),
                Tables\Columns\TextColumn::make('created_at')
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListCustomerFeedback::route('/'),
            // 'create' => Pages\CreateCustomerFeedback::route('/create'),
            // 'view' => Pages\ViewCustomerFeedback::route('/{record}'),
            // 'edit' => Pages\EditCustomerFeedback::route('/{record}/edit'),
        ];
    }

    public static function getlModelLabel(): string
    {
        return __('Customer Feedback');
    }
    public static function getPluralModelLabel(): string
    {
        return __('Customer Feedback');
    }
}
