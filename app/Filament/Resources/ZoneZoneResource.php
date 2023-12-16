<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ZoneZoneResource\Pages;
use App\Filament\Resources\ZoneZoneResource\RelationManagers;
use Filament\Resources\Form;
use App\Forms\Components\DrawZone;
use App\Forms\Components\ShowZone;
use App\Models\ResCompany;
use App\Models\ResPartner;
use App\Models\ZoneZone;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ViewField;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ZoneZoneResource extends Resource
{
    protected static ?string $model = ZoneZone::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';
    public static function getNavigationGroup(): ?string
    {
        return __('System Settings');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Select::make('company_id')
                    ->relationship('company', 'name')
                    ->label('Company')
                    ->reactive()
                    ->required()
                    ->translateLabel(),

                ColorPicker::make('marker_color')->translateLabel(),
                TextInput::make('name')
                    ->required()
                    ->translateLabel(),
                TextInput::make('delivery_fees')
                    ->visible(function (callable $get) {
                        $company_id = $get('company_id');
                        $company = ResCompany::find($company_id);
                        if ($company) {
                            if ($company->fees_type == 1) {
                                return true;
                            } else {
                                return false;
                            }
                        } else {
                            return true;
                        }
                    })
                    ->numeric()->translateLabel(),
                TextInput::make('delivery_time')
                    ->visible(function (callable $get) {
                        $company_id = $get('company_id');
                        $company = ResCompany::find($company_id);
                        if ($company) {
                            if ($company->delivery_time_type == 1) {
                                return true;
                            } else {
                                return false;
                            }
                        } else {
                            return true;
                        }
                    })
                    ->numeric()->translateLabel(),

                Section::make('')->schema([DrawZone::make('zone')->translateLabel()])->visibleOn('edit'),
                Section::make('')->schema([Placeholder::make('Please Create to draw the zone')->translateLabel()])->visibleOn('create'),


                // TextInput::make('get_map'),
                // TextInput::make('get_geo_lines'),
                // TextInput::make('get_drawing'),
                // Toggle::make('show_fee'),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->translateLabel(),
                TextColumn::make('company.name')->translateLabel(),
                TextColumn::make('delivery_fees')->translateLabel(),
            ])
            ->filters([
                //
            ])
            ->actions([


                Tables\Actions\Action::make('show')
                    ->label('show')
                    ->url(fn (ZoneZone $record): string => route('filament.resources.zone-zones.show', ['record' => $record])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListZoneZones::route('/'),
            'create' => Pages\CreateZoneZone::route('/create'),
            'edit' => Pages\EditZoneZone::route('/{record}/edit'),
            'all' => Pages\AllZones::route('/all'),
            'show' => Pages\ShowZone::route('/{record}/show')
        ];
    }

    public static function getlModelLabel(): string
    {
        return __('Zones');
    }
    public static function getPluralModelLabel(): string
    {
        return __('Zones');
    }
    
}
