<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Tenant\ResCompany;
use App\Models\ResPartner;
use App\Models\Tenant\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Form;
use Filament\Resources\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    public static function getNavigationGroup(): ?string
    {
        return __('User Management');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required()->translateLabel(),
                TextInput::make('login')->required()->translateLabel(),
                FileUpload::make('image')
                    ->disk('public')->directory('images/User')
                    ->image()
                    ->translateLabel(),
                TextInput::make('email')->translateLabel(),

                TextInput::make('password')
                    ->password()
                    ->minLength(8)
                    ->confirmed()
                    ->required()
                    ->hiddenOn('edit')
                    ->translateLabel(),
                TextInput::make('password_confirmation')
                    ->password()
                    ->hiddenOn('edit')
                    ->required()
                    ->translateLabel(),
                Select::make('partner_id')
                    ->translateLabel()
                    ->relationship('partner', 'name'),

                Select::make('company_id')
                    ->options(ResCompany::all()->pluck('name', 'id'))
                    ->relationship('defaultCompany', 'name')
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                        $set('AllowedCompanies', [$state]);
                    })
                    ->translateLabel()
                    ->nullable(),
                Select::make('AllowedCompanies')
                    ->options(ResCompany::all()->pluck('name', 'id'))
                    ->relationship('AllowedCompanies', 'name')
                    ->multiple()
                    ->nullable()
                    ->translateLabel(),
                Toggle::make('active')->default(true)->translateLabel(),
                // TextInput::make('signature')->nullable()->translateLabel(),
                // Toggle::make('share')->nullable()->translateLabel(),
                // TextInput::make('notification_type')->nullable()->translateLabel(),
                // TextInput::make('livechat_username')->nullable()->translateLabel(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable()->translateLabel(),
                ImageColumn::make('image')->translateLabel(),
                TextColumn::make('email')->sortable()->translateLabel(),
                ToggleColumn::make('active')->sortable()->translateLabel(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getlModelLabel(): string
    {
        return __('user');
    }
    public static function getPluralModelLabel(): string
    {
        return __('Users');
    }
}
