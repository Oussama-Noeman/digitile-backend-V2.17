<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WebsiteFaqResource\Pages;
use App\Filament\Resources\WebsiteFaqResource\RelationManagers;
use App\Models\WebsiteFaq;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Form;
use Filament\Resources\Table;
class WebsiteFaqResource extends Resource
{
    protected static ?string $model = WebsiteFaq::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    public static function getNavigationGroup(): ?string
    {
        return __('Help & Support Section');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               Textarea::make('name')->translateLabel(),
               Textarea::make('answer')->translateLabel(),
               Select::make('company_id')
               ->relationship("company", "name"),
               FileUpload::make('banner')
                     ->translateLabel()
                    ->disk('public')->directory('images/banner')
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
            ->columns([
                ImageColumn::make('banner')->translateLabel(),
                TextColumn::make('name')->translateLabel(),
                TextColumn::make('answer')->translateLabel(),
                
            ])
            ->filters([
                //
            ])
            ->actions([
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
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWebsiteFaqs::route('/'),
            'create' => Pages\CreateWebsiteFaq::route('/create'),
            'edit' => Pages\EditWebsiteFaq::route('/{record}/edit'),
        ];
    }    
    
    public static function getlModelLabel(): string
    {
        return __('Website Faq');
    }
public static function getPluralModelLabel(): string
{
    return __('Website Faq');
}
}
