<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HrJobResource\Pages;
use App\Filament\Resources\HrJobResource\RelationManagers;
use App\Models\Tenant\HrJob;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Resources\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HrJobResource extends Resource
{
    protected static ?string $model = HrJob::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    public static function getNavigationGroup(): ?string
    {
        return __('Recruitment');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make("company_id")
                    ->relationship("company", "name")
                    ->required(),
                TextInput::make("name")
                    ->required(),
                Textarea::make("description")
                    ->required(),
                Toggle::make("active")
                    ->default(true),
                Toggle::make("is_published"),
                FileUpload::make('image')
                    ->disk('public')->directory('images/HrJobs')
                    ->image()
                // ->imageEditor()
                // ->imageEditorAspectRatios([
                //     '16:9',
                //     '4:3',
                //     '1:1',
                // ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("company_id"),
                TextColumn::make("name"),
                TextColumn::make("image"),
                TextColumn::make("description"),
                ToggleColumn::make("active"),
                ToggleColumn::make("is_published"),
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
            'index' => Pages\ListHrJobs::route('/'),
            'create' => Pages\CreateHrJob::route('/create'),
            'edit' => Pages\EditHrJob::route('/{record}/edit'),
        ];
    }
    public static function getlModelLabel(): string
    {
        return __('Jobs');
    }
    public static function getPluralModelLabel(): string
    {
        return __('Jobs');
    }
}
