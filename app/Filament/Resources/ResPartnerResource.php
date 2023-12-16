<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResPartnerResource\Pages;
use App\Filament\Resources\ResPartnerResource\RelationManagers;
use App\Forms\Components\SetMarker;
use App\Models\Tenant\DigitileKitchen;
use App\Models\ProductTemplate;
use App\Models\Tenant\ResCompany;
use App\Models\Tenant\ResPartner;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Crypt;
use Filament\Resources\Form;
use Filament\Resources\Table;
class ResPartnerResource extends Resource
{
    protected static ?string $model = ResPartner::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    public static function getNavigationGroup(): ?string
{
    return __('User Management');
}
    // protected static ?string $tenantOwnershipRelationshipName = 'company';
    public static function form(Form $form): Form
    {
       
        return $form
            ->schema([
                Select::make('parent_id')->relationship('parent', 'id')
                    ->options(ResPartner::all()->pluck('name', 'id'))
                    ,

                Select::make('company_id')
                    ->relationship('company', 'name')
                    ->options(function () {
                        $companies = ResCompany::all()->pluck('name', 'id');
                        // dd($companies);
                        return $companies;
                    })->reactive()
                    ->label('Company')->translateLabel(),
                TextInput::make('name')->translateLabel(),
                TextInput::make('display_name')->translateLabel(),
                TextInput::make('ref')->translateLabel(),
                TextInput::make('position')->label('Job Position')->translateLabel(),
                TextInput::make('phone')->translateLabel(),
                TextInput::make('mobile')->translateLabel(),
                TextInput::make('email')->translateLabel(),
                TextInput::make('lang')->translateLabel(),

                Select::make('type')
                    ->options([
                        'option1' => 'Company',
                        'option2' => 'Individual',
                    ])->translateLabel(),
                Section::make('ADRESS DETAILS')
                    ->translateLabel()
                    ->schema([
                        TextInput::make('city')->translateLabel(),
                        TextInput::make('street')->translateLabel(),
                        TextInput::make('street2')->translateLabel(),
                    ])->translateLabel(),
                FileUpload::make('team_image_attachment')
                    ->label('Image')
                    ->disk('public')->directory('images/PartenerCategory')
                    ->image()
                    ->translateLabel()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '16:9',
                        '4:3',
                        '1:1',
                    ]),
                Radio::make('role')
                    ->options([
                        'is_client' => 'is_client ',
                        'is_driver' => 'is_driver',
                        'is_member' => 'is_member',
                        'is_manager' => 'is_manager',
                        'is_chef' => 'is_chef',
                       
                    ])->reactive()
                    ->translateLabel()
                    ->required(),
                    Select::make('kitchen_id')
                    ->translateLabel()
                    ->relationship('kitchen', 'name', function ($query, $get) {
                        $query->where('company_id', $get('company_id'));
                    })
                    ->options(function ($get) {
                        $comp = $get('company_id');
                        $kitchen = DigitileKitchen::where('company_id', $comp)->get();
                        return $kitchen;
                    })
                    ->visible(function ($get) {
                        $chef = $get('role');
                        return $chef == 'is_chef';
                    })
                ]);
            //     Actions::make([
            //         Action::make('create user')
                     
            //             ->label('Create User')
            //             ->translateLabel()
            //             ->form([
            //                 TextInput::make('name'),
            //                 TextInput::make('login'),
            //                 TextInput::make('email'),
            //                 TextInput::make('password')
            //                     ->password()
            //                     ->confirmed(),
            //                 TextInput::make('password_confirmation')
            //                     ->password(),
            //             ])
            //             ->action(function (array $data, ResPartner $record) {
            //                 //                          dd($data['name']);
            //                 $user = User::create([
            //                     'name' => $data['name'],
            //                     'email' => $data['email'],
            //                     'login' => $data['login'],
            //                     'company_id' => $record->company_id,
            //                     'password' => bcrypt($data['password']),
            //                     'partner_id' => $record->id
            //                 ]);
            //                 $record->user_id = $user->id;
            //                 $record->save();
            //                 Notification::make()
            //                     ->success()
            //                     ->title('User Created')
            //                     ->persistent()
            //                     ->send();
            //             })

            //     ])->hiddenOn('create')
            //         ->visible(function (ResPartner $record) {
            //             $user = User::where('partner_id', $record->id)->first();
            //             // dd($user);
            //             if ($user) {
            //                 return false;
            //             } else {
            //                 return true;
            //             }
            //         }),
            //     Section::make('')->schema([Placeholder::make('Please Create before adding your address')->translateLabel()])->visibleOn('create'),
            //     Section::make('')->schema([SetMarker::make('latt_long')->label('adress')->translateLabel()])->visibleOn('edit'),


            // ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('users.name')->translateLabel(),
                ImageColumn::make('team_image_attachment')->label('Image')->translateLabel(),

//                TextColumn::make('parent.name'),
//                TextColumn::make('company.name.en'),
//                TextColumn::make('display_name'),
//                TextColumn::make('ref'),
//                TextColumn::make('lang'),
                TextColumn::make('city')->translateLabel(),
                TextColumn::make('street')->translateLabel(),
//                TextColumn::make('email'),
//                TextColumn::make('phone'),
//                TextColumn::make('mobile'),
//                TextColumn::make('partner_latitude'),
//                TextColumn::make('partner_longitude'),
//                ToggleColumn::make('is_client'),
//                ToggleColumn::make('is_driver'),
//                ToggleColumn::make('is_member'),
//                ToggleColumn::make('is_main'),
//                ToggleColumn::make('position'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
             
            ]);
            
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
            'index' => Pages\ListResPartners::route('/'),
            'create' => Pages\CreateResPartner::route('/create'),
            'edit' => Pages\EditResPartner::route('/{record}/edit'),
        ];
    }

    public static function getlModelLabel(): string
    {
        return __('Contacts');
    }
public static function getPluralModelLabel(): string
{
    return __('Contacts');
}
}
