<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamResource\Pages;
use App\Filament\Resources\TeamResource\RelationManagers;
use App\Models\ResCompany;
use App\Models\Team;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Form;
use Filament\Resources\Table;
class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    
    public static function getNavigationGroup(): ?string
    {
        return __('Website Settings');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->schema([
                        Forms\Components\Select::make('company_id')
                            ->relationship('company', 'name')
                             ->translateLabel()
                            ->nullable(),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->translateLabel()
                            ->maxLength(255),
                    ])->columnSpan(2)->columns(1),
                Section::make('')
                    ->schema([
                        Forms\Components\FileUpload::make('team_image_attachment')
                            ->image()
                            ->label('Image')
                            ->translateLabel(),

                    ])->columnSpan(1),
            ])->columns(3);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_id')
                  ->label("Company")
                    ->numeric()
                    ->translateLabel()
                    ->sortable()
                    ->formatStateUsing(function (string $state) {
                        $name= ResCompany::where('id', $state)->pluck('name')[0];
                        return $name;
                    }),
                Tables\Columns\TextColumn::make('name')
                ->translateLabel()
                    ->searchable(),
                Tables\Columns\ImageColumn::make('team_image_attachment')
                ->label('Image')
                ->translateLabel(),
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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                ])
            ;
          
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\MembersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeams::route('/'),
            'create' => Pages\CreateTeam::route('/create'),
            'view' => Pages\ViewTeam::route('/{record}'),
            'edit' => Pages\EditTeam::route('/{record}/edit'),
        ];
    }
    
    public static function getlModelLabel(): string
    {
        return __('Team');
    }
public static function getPluralModelLabel(): string
{
    return __('Team');
}
}